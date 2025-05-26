CREATE DATABASE IF NOT EXISTS sampi_order_db;
USE sampi_order_db;

-- Table to store customer information
CREATE TABLE customers (
    customer_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    contact_number VARCHAR(20) NOT NULL,
    house_address VARCHAR(255) NOT NULL,
    barangay VARCHAR(100) NOT NULL,
    city VARCHAR(100) NOT NULL,
    country VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table to store orders
CREATE TABLE IF NOT EXISTS orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    service_category VARCHAR(100) NOT NULL,
    service_item VARCHAR(255) NOT NULL,
    custom_name VARCHAR(255),
    color VARCHAR(50),
    size_length_cm DECIMAL(10, 2),
    size_width_cm DECIMAL(10, 2),
    size_height_cm DECIMAL(10, 2),
    quantity INT NOT NULL,
    estimated_time VARCHAR(100),
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(customer_id)
        ON DELETE CASCADE
);
ALTER TABLE orders ADD status ENUM('Pending', 'Complete') DEFAULT 'Pending';
ALTER TABLE orders ADD completed_at DATETIME NULL;


SELECT o.order_id, c.first_name, c.last_name, c.email, c.contact_number,
                       o.service_category, o.service_item, o.custom_name, o.color,
                       o.size_length_cm, o.size_width_cm, o.size_height_cm,
                       o.quantity, o.estimated_time, o.order_date
                FROM orders o
                JOIN customers c ON o.customer_id = c.customer_id;
