<?php
include 'db_config.php';

$service = isset($_GET['service']) ? htmlspecialchars($_GET['service']) : '';
$success = false;
$error = '';

session_start();

// Generate CSRF token if not exists
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error = "Security error: Invalid request!";
    } else {
        // Get and validate form data
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $service = trim($_POST['service'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $budget = trim($_POST['budget'] ?? '');
        $preferred_date = trim($_POST['preferred_date'] ?? '');
        $address = trim($_POST['address'] ?? '');

        // Validate form data
        if (empty($name) || empty($email) || empty($phone) || empty($service)) {
            $error = "Please fill in all required fields!";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Invalid email format!";
        } elseif (!preg_match('/^[0-9]{10}$/', preg_replace('/[^0-9]/', '', $phone))) {
            $error = "Invalid phone number! Please enter a 10-digit number.";
        } else {
            // Sanitize inputs
            $name = htmlspecialchars($name);
            $email = htmlspecialchars($email);
            $phone = htmlspecialchars($phone);
            $service = htmlspecialchars($service);
            $description = htmlspecialchars($description);
            $budget = htmlspecialchars($budget);
            $preferred_date = htmlspecialchars($preferred_date);
            $address = htmlspecialchars($address);

            // Insert into database with prepared statement
            $sql = "INSERT INTO bookings (name, email, phone, service, description, budget, preferred_date, address) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                $error = "Database error: " . htmlspecialchars($conn->error);
            } else {
                $stmt->bind_param("ssssssss", $name, $email, $phone, $service, $description, $budget, $preferred_date, $address);
                
                if ($stmt->execute()) {
                    $success = true;
                    $_POST = array();
                } else {
                    $error = "Error submitting booking. Please try again later.";
                }
                $stmt->close();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Service - Hari Carpenter</title>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' https://cdn.jsdelivr.net; style-src 'self' https://cdn.jsdelivr.net 'unsafe-inline'; font-src 'self' https://cdn.jsdelivr.net; img-src 'self' data:">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .registration-container {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 20px 0;
        }
        .form-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            padding: 40px;
        }
        .form-card h2 {
            color: #333;
            margin-bottom: 30px;
            text-align: center;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn-submit {
            background: #667eea;
            border: none;
            padding: 10px 30px;
            font-weight: bold;
        }
        .btn-submit:hover {
            background: #764ba2;
            color: white;
        }
        .alert {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="registration-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="form-card">
                    <h2>📋 Book Your Service</h2>

                    <?php if ($success): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success!</strong> Your booking has been submitted. We will contact you soon!
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <div class="text-center mt-4">
                            <a href="index.html" class="btn btn-primary">Back to Home</a>
                        </div>
                    <?php else: ?>
                        <?php if ($error): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
                            
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name *</label>
                                <input type="text" class="form-control" id="name" name="name" required value="<?php echo htmlspecialchars($_POST['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address *</label>
                                <input type="email" class="form-control" id="email" name="email" required value="<?php echo htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number *</label>
                                <input type="tel" class="form-control" id="phone" name="phone" placeholder="10 digit number" required value="<?php echo htmlspecialchars($_POST['phone'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>

                            <div class="mb-3">
                                <label for="service" class="form-label">Select Service *</label>
                                <select class="form-control" id="service" name="service" required>
                                    <option value="">-- Choose Service --</option>
                                    <option value="furniture" <?php if ($service == 'furniture') echo 'selected'; ?>>Furniture Making</option>
                                    <option value="kitchen" <?php if ($service == 'kitchen') echo 'selected'; ?>>Modular Kitchen</option>
                                    <option value="wardrobe" <?php if ($service == 'wardrobe') echo 'selected'; ?>>Wardrobes</option>
                                    <option value="pooja" <?php if ($service == 'pooja') echo 'selected'; ?>>Pooja Room</option>
                                    <option value="hall" <?php if ($service == 'hall') echo 'selected'; ?>>Hall Furniture</option>
                                    <option value="dressing_table" <?php if ($service == 'dressing_table') echo 'selected'; ?>>Dressing Table</option>
                                    <option value="bathroom" <?php if ($service == 'bathroom') echo 'selected'; ?>>Bathroom Fixtures</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Project Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3" placeholder="Tell us about your project..."><?php echo htmlspecialchars($_POST['description'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="budget" class="form-label">Budget Range</label>
                                <input type="text" class="form-control" id="budget" name="budget" placeholder="e.g., ₹10,000 - ₹20,000" value="<?php echo htmlspecialchars($_POST['budget'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>

                            <div class="mb-3">
                                <label for="preferred_date" class="form-label">Preferred Start Date</label>
                                <input type="date" class="form-control" id="preferred_date" name="preferred_date" value="<?php echo htmlspecialchars($_POST['preferred_date'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control" id="address" name="address" rows="2" placeholder="Your project location..."><?php echo htmlspecialchars($_POST['address'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                            </div>

                            <button type="submit" class="btn btn-submit btn-lg w-100">Submit Booking</button>
                        </form>

                        <div class="text-center mt-3">
                            <a href="index.html">Back to Home</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php
$conn->close();
?>
