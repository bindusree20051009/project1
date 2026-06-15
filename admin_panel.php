<?php
session_start();
include 'db_config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$message = '';

// Delete booking
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $sql = "DELETE FROM bookings WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        $message = "<div class='alert alert-success'>Booking deleted successfully!</div>";
    }
}

// Update booking status
if (isset($_POST['update_status'])) {
    $id = intval($_POST['booking_id']);
    $status = htmlspecialchars($_POST['status']);
    $sql = "UPDATE bookings SET status = '$status' WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        $message = "<div class='alert alert-success'>Status updated successfully!</div>";
    }
}

// Fetch all bookings
$sql = "SELECT * FROM bookings ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Hari Carpenter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f5f5f5;
        }
        .navbar-custom {
            background: #333;
            color: white;
        }
        .admin-container {
            padding: 20px 0;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .table-responsive {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
        }
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-pending {
            background: #ffc107;
            color: black;
        }
        .status-confirmed {
            background: #28a745;
            color: white;
        }
        .status-completed {
            background: #007bff;
            color: white;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container">
        <h3 class="text-white mb-0">Hari Carpenter - Admin Panel</h3>
        <div class="ms-auto">
            <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
        </div>
    </div>
</nav>

<div class="admin-container">
    <div class="container">
        <?php echo $message; ?>

        <div class="table-responsive">
            <h2 class="mb-4">📋 Service Bookings</h2>
            
            <?php if ($result->num_rows > 0): ?>
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Service</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td>#<?php echo $row['id']; ?></td>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                <td><?php echo ucfirst(htmlspecialchars($row['service'])); ?></td>
                                <td><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
                                <td>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="booking_id" value="<?php echo $row['id']; ?>">
                                        <select name="status" class="form-select form-select-sm" onchange="this.form.submit();">
                                            <option value="pending" <?php if ($row['status'] == 'pending') echo 'selected'; ?>>Pending</option>
                                            <option value="confirmed" <?php if ($row['status'] == 'confirmed') echo 'selected'; ?>>Confirmed</option>
                                            <option value="completed" <?php if ($row['status'] == 'completed') echo 'selected'; ?>>Completed</option>
                                        </select>
                                        <input type="hidden" name="update_status" value="1">
                                    </form>
                                </td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#detailModal<?php echo $row['id']; ?>">View</a>
                                    <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?');">Delete</a>
                                </td>
                            </tr>

                            <!-- Detail Modal -->
                            <div class="modal fade" id="detailModal<?php echo $row['id']; ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Booking Details</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p><strong>Name:</strong> <?php echo htmlspecialchars($row['name']); ?></p>
                                            <p><strong>Email:</strong> <?php echo htmlspecialchars($row['email']); ?></p>
                                            <p><strong>Phone:</strong> <?php echo htmlspecialchars($row['phone']); ?></p>
                                            <p><strong>Service:</strong> <?php echo ucfirst(htmlspecialchars($row['service'])); ?></p>
                                            <p><strong>Description:</strong> <?php echo htmlspecialchars($row['description']); ?></p>
                                            <p><strong>Budget:</strong> <?php echo htmlspecialchars($row['budget']); ?></p>
                                            <p><strong>Preferred Date:</strong> <?php echo htmlspecialchars($row['preferred_date']); ?></p>
                                            <p><strong>Address:</strong> <?php echo htmlspecialchars($row['address']); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-center text-muted">No bookings yet.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php
$conn->close();
?>
