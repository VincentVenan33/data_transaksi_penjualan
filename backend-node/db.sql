CREATE DATABASE IF NOT EXISTS inventory_db;
USE inventory_db;

CREATE TABLE products (
    id VARCHAR(50) PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(12,2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    category VARCHAR(50)
);

CREATE TABLE customers (
  id VARCHAR(50) PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100),
  deleted_at TIMESTAMP NULL DEFAULT NULL
);

CREATE TABLE transactions (
  id VARCHAR(50) PRIMARY KEY,
  productId VARCHAR(50),
  customerId VARCHAR(50),
  quantity INT,
  type ENUM('IN','OUT'),
  created_at TIMESTAMP DEFAULT (CONVERT_TZ(NOW(),'+00:00','+07:00')),
  deleted_at TIMESTAMP NULL DEFAULT NULL,
  FOREIGN KEY (productId) REFERENCES products(id),
  FOREIGN KEY (customerId) REFERENCES customers(id)
);
