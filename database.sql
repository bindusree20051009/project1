-- Create Database
CREATE DATABASE IF NOT EXISTS hari_carpenter;
USE hari_carpenter;

-- Create Bookings Table
CREATE TABLE IF NOT EXISTS bookings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    service VARCHAR(50) NOT NULL,
    description TEXT,
    budget VARCHAR(50),
    preferred_date DATE,
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(20) DEFAULT 'pending'
);

-- Create Services Table
CREATE TABLE IF NOT EXISTS services (
    id INT PRIMARY KEY AUTO_INCREMENT,
    service_name VARCHAR(100) NOT NULL,
    description TEXT,
    starting_price INT,
    icon VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert Services
INSERT INTO services (service_name, description, starting_price) VALUES
('Furniture Making', 'Custom sofas, tables, beds and wooden furniture.', 5000),
('Modular Kitchen', 'Modern kitchen designs with premium finish.', 15000),
('Wardrobes', 'Stylish cupboards and storage solutions.', 8000);

-- Create Testimonials Table
CREATE TABLE IF NOT EXISTS testimonials (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    rating INT CHECK (rating >= 1 AND rating <= 5),
    comment TEXT,
    service VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    approved BOOLEAN DEFAULT FALSE
);

-- Create Gallery Table
CREATE TABLE IF NOT EXISTS gallery (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    image_url VARCHAR(255),
    service VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create Admin Users Table
CREATE TABLE IF NOT EXISTS admin_users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
