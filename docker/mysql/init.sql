CREATE DATABASE IF NOT EXISTS steampunk_construction;
USE steampunk_construction;

-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    middle_name VARCHAR(50),
    last_name VARCHAR(50) NOT NULL,
    birthday DATE NOT NULL,
    address TEXT NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20),
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('customer', 'admin') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Products table
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    quality_rating INT(1) DEFAULT 3,
    image_path VARCHAR(255),
    stock_quantity INT NOT NULL,
    steampunk_style_level INT(2) DEFAULT 5,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Orders table
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    status ENUM('cart', 'pending', 'completed', 'shipped', 'cancelled') DEFAULT 'cart',
    total_amount DECIMAL(10,2),
    payment_method VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Order items table
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Password resets table
CREATE TABLE IF NOT EXISTS password_resets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token VARCHAR(64) NOT NULL,
    expires_at DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Sample data
INSERT INTO products (name, description, price, quality_rating, stock_quantity, steampunk_style_level) VALUES
('Brass Gear Bolts', 'High-quality brass bolts with gear-shaped heads', 12.99, 4, 100, 8),
('Steam-Powered Hammer', 'Victorian-era hammer with steam pressure mechanism', 89.99, 5, 25, 9),
('Copper Pipe Joints', 'Decorative copper piping for steampunk construction', 24.50, 3, 75, 7),
('Clockwork Rivets', 'Precision rivets with clockwork detailing', 8.75, 4, 200, 6),
('Tesla\'s Brass Plates', 'Electrified brass plating for special projects', 45.25, 5, 40, 9);

INSERT INTO users (first_name, last_name, birthday, address, email, phone, username, password, role) VALUES
('Admin', 'Admin', '1990-01-01', 'Admin Address', 'admin2@steampunk.com', '555-0000', 'admin', 'admin', 'admin');