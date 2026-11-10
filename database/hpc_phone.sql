CREATE DATABASE IF NOT EXISTS hpc_phone CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE hpc_phone;

CREATE TABLE users (
 id INT AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(120) NOT NULL,
 email VARCHAR(190) NOT NULL UNIQUE,
 password VARCHAR(255) NOT NULL,
 role ENUM('admin','customer') NOT NULL DEFAULT 'customer',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE products (
 id INT AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(190) NOT NULL,
 price DECIMAL(12,2) NOT NULL,
 sale_price DECIMAL(12,2) NULL,
 image VARCHAR(500) NULL,
 description TEXT NULL,
 stock INT NOT NULL DEFAULT 0,
 status TINYINT(1) NOT NULL DEFAULT 1,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 INDEX idx_products_status(status)
) ENGINE=InnoDB;

CREATE TABLE orders (
 id INT AUTO_INCREMENT PRIMARY KEY,
 user_id INT NOT NULL,
 customer_name VARCHAR(120) NOT NULL,
 phone VARCHAR(30) NOT NULL,
 address TEXT NOT NULL,
 total DECIMAL(12,2) NOT NULL,
 status ENUM('pending','confirmed','shipping','completed','cancelled') DEFAULT 'pending',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 CONSTRAINT fk_orders_user FOREIGN KEY(user_id) REFERENCES users(id)
) ENGINE=InnoDB;

CREATE TABLE order_items (
 id INT AUTO_INCREMENT PRIMARY KEY,
 order_id INT NOT NULL,
 product_id INT NOT NULL,
 product_name VARCHAR(190) NOT NULL,
 price DECIMAL(12,2) NOT NULL,
 quantity INT NOT NULL,
 CONSTRAINT fk_items_order FOREIGN KEY(order_id) REFERENCES orders(id),
 CONSTRAINT fk_items_product FOREIGN KEY(product_id) REFERENCES products(id)
) ENGINE=InnoDB;

INSERT INTO products(name,price,sale_price,image,description,stock) VALUES
('iPhone 15 128GB',18990000,16990000,'https://placehold.co/600x450?text=iPhone+15','Điện thoại Apple iPhone 15, bộ nhớ 128GB.',15),
('Samsung Galaxy S24 256GB',21990000,19490000,'https://placehold.co/600x450?text=Galaxy+S24','Flagship Samsung Galaxy S24, bộ nhớ 256GB.',12),
('Xiaomi 14T 256GB',12990000,10990000,'https://placehold.co/600x450?text=Xiaomi+14T','Điện thoại Xiaomi 14T, bộ nhớ 256GB.',20),
('OPPO Reno12 5G',11990000,9990000,'https://placehold.co/600x450?text=OPPO+Reno12','Điện thoại OPPO Reno12 5G.',10);

-- Tạo tài khoản Admin bằng cách đăng ký tài khoản trên website,
-- sau đó chạy câu lệnh sau với email tương ứng:
-- UPDATE users SET role='admin' WHERE email='admin@example.com';
