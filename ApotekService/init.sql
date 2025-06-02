CREATE DATABASE apotek_db;

USE apotek_db;

CREATE TABLE obat (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100),
  stock INT,
  price DECIMAL(10,2)
);

CREATE TABLE suppliers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100),
  contact VARCHAR(50)
);

CREATE TABLE orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  obat_id INT,
  quantity INT,
  order_date DATE
);