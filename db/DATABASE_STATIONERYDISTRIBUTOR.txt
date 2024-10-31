CREATE DATABASE stationeryDistributor;

USE stationeryDistributor;


CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    roleName VARCHAR(50) NOT NULL,
    description VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table for storing users
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstName VARCHAR(100) NOT NULL,
    lastName VARCHAR(100) NOT NULL,
    userName VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone INT NOT NULL,
    password VARCHAR(255) NOT NULL,
    role_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(id)
);


CREATE TABLE clients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstName VARCHAR(100) NOT NULL,
    lastName VARCHAR(100) NOT NULL,
    address VARCHAR(255),
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(15),

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


CREATE TABLE suppliers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    supplierName VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    phone VARCHAR(15),
    address VARCHAR(255),
    city VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE inventory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    bookName VARCHAR(100) NOT NULL, 
    publisher VARCHAR(255),  
    stock INT NOT NULL,  
	purchase_price DECIMAL(10, 2),     
    sale_price DECIMAL(10, 2),         

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Sales table
CREATE TABLE sales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date DATE NOT NULL,
    employee_id INT, 
    client_id INT,
    total DECIMAL(10, 2) NOT NULL DEFAULT 0,
    FOREIGN KEY (employee_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE
);

SELECT * FROM sales

-- Sales Detail table
CREATE TABLE sales_detail (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sale_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(10, 2) NOT NULL,
    subtotal DECIMAL(10, 2) AS (quantity * unit_price) STORED,
    FOREIGN KEY (sale_id) REFERENCES sales(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES inventory(id) ON DELETE RESTRICT
);

SELECT * FROM sales_detail

DELIMITER $$
-- Trigger to update total on insert into sales_detail
CREATE TRIGGER update_sale_total_insert AFTER INSERT ON sales_detail
FOR EACH ROW
BEGIN
    UPDATE sales
    SET total = (SELECT SUM(subtotal) FROM sales_detail WHERE sale_id = NEW.sale_id)
    WHERE id = NEW.sale_id;
END $$
DELIMITER ;


DELIMITER $$
-- Trigger to update total on update in sales_detail
CREATE TRIGGER update_sale_total_update AFTER UPDATE ON sales_detail
FOR EACH ROW
BEGIN
    UPDATE sales
    SET total = (SELECT SUM(subtotal) FROM sales_detail WHERE sale_id = NEW.sale_id)
    WHERE id = NEW.sale_id;
END $$
DELIMITER ;


DELIMITER $$
-- Trigger to update total on delete from sales_detail
CREATE TRIGGER update_sale_total_delete AFTER DELETE ON sales_detail
FOR EACH ROW
BEGIN
    UPDATE sales
    SET total = (SELECT COALESCE(SUM(subtotal), 0) FROM sales_detail WHERE sale_id = OLD.sale_id)
    WHERE id = OLD.sale_id;
END $$
DELIMITER ;


-- Trigger para reducir el stock al agregar un detalle de venta
DELIMITER $$
-- Trigger para reducir el stock al agregar un detalle de venta
CREATE TRIGGER reduce_stock_after_sale
AFTER INSERT ON sales_detail
FOR EACH ROW
BEGIN
    UPDATE inventory
    SET stock = stock - NEW.quantity
    WHERE id = NEW.product_id;
END $$
DELIMITER ;



-- Insert default roles
INSERT INTO roles (roleName, description) VALUES ('Admin', 'System Administrator');
INSERT INTO roles (roleName, description) VALUES ('User', 'General User');

-- Insert default user (admin)
INSERT INTO users (firstName,lastName, userName, email, phone, password, role_id) 
VALUES ('Admin User', 'ANT','admin', 'admin@example.com', 79009900, MD5('1234'), 1);