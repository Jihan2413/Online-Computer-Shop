CREATE DATABASE IF NOT EXISTS computer_shop;
USE computer_shop;

CREATE TABLE IF NOT EXISTS users (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    name            VARCHAR(100) NOT NULL,
    email           VARCHAR(150) NOT NULL UNIQUE,
    password_hash   VARCHAR(255) NOT NULL,
    role            ENUM('admin','customer') DEFAULT 'customer',
    profile_picture VARCHAR(255) DEFAULT NULL,
    created_at      DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS categories (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100) NOT NULL,
    parent_id  INT DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS brands (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(100) NOT NULL,
    category_id INT NOT NULL,
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS products (
    id                  INT AUTO_INCREMENT PRIMARY KEY,
    name                VARCHAR(200) NOT NULL,
    description         TEXT,
    manufacturer_review TEXT,
    price               DECIMAL(10,2) NOT NULL,
    category_id         INT NOT NULL,
    brand_id            INT NOT NULL,
    image_path          VARCHAR(255) DEFAULT NULL,
    stock               INT DEFAULT 0,
    created_at          DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS cart (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    user_id    INT NOT NULL,
    product_id INT NOT NULL,
    quantity   INT DEFAULT 1,
    added_at   DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS orders (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    user_id        INT DEFAULT NULL,
    total_amount   DECIMAL(10,2) NOT NULL,
    payment_method ENUM('cash_on_delivery','online_wallet') NOT NULL,
    status         VARCHAR(50) DEFAULT 'pending',
    order_date     DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS order_items (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    order_id   INT NOT NULL,
    product_id INT DEFAULT NULL,
    quantity   INT NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL
);

CREATE TABLE IF NOT EXISTS reviews (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    product_id    INT NOT NULL,
    user_id       INT DEFAULT NULL,
    reviewer_name VARCHAR(100) NOT NULL,
    comment       TEXT NOT NULL,
    created_at    DATETIME DEFAULT CURRENT_TIMESTAMP
);


INSERT IGNORE INTO users (id, name, email, password_hash, role) VALUES
(1, 'Admin User',    'admin@shop.com',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
(2, 'Test Customer', 'customer@shop.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer');


INSERT IGNORE INTO categories (id, name, parent_id) VALUES
(1, 'RAM', NULL),
(2, 'Monitor', NULL),
(3, 'Storage', NULL);


INSERT IGNORE INTO brands (id, name, category_id) VALUES
(1, 'Corsair', 1),
(2, 'ASUS', 2),
(3, 'Seagate', 3);


INSERT IGNORE INTO products (id, name, description, manufacturer_review, price, category_id, brand_id, stock) VALUES
(1, 'Corsair 16GB DDR5 RAM', 'High speed gaming RAM', 'Best performance in tests', 89.99, 1, 1, 20),
(2, 'ASUS 27 inch Monitor', '4K IPS Display', 'Excellent color accuracy', 399.00, 2, 2, 10),
(3, 'Seagate 2TB HDD', 'Desktop hard drive 7200 RPM', 'Reliable storage solution', 59.99, 3, 3, 35);


INSERT IGNORE INTO cart (user_id, product_id, quantity) VALUES
(2, 1, 2),
(2, 3, 1);
