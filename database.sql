;

-- Admins table with secure password storage
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    INDEX idx_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Bookings table
CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    service VARCHAR(50) NOT NULL,
    description LONGTEXT,
    budget VARCHAR(50),
    preferred_date DATE,
    address LONGTEXT,
    status VARCHAR(20) DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_phone (phone),
    INDEX idx_service (service),
    INDEX idx_status (status),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert a default admin user (PASSWORD MUST BE CHANGED!)
-- Default password hash for "admin@123": $2y$10$...
-- Generate with: php -r "echo password_hash('YourSecurePassword123!', PASSWORD_BCRYPT);"
-- IMPORTANT: Change the password immediately after setup!
INSERT INTO admins (username, password, email) 
VALUES ('admin', '$2y$10$dXVm8HqZU8zIeqBg6P1J3eZxF/2E3C4D5E6F7G8H9I0J1K2L3M4N5O6', 'admin@haricp.com')
ON DUPLICATE KEY UPDATE password=VALUES(password);
