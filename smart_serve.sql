-- =====================================================
-- Smart Serve Restaurant Ordering System Database
-- QR Code-Based Restaurant Management
-- =====================================================

-- Create Database
CREATE DATABASE IF NOT EXISTS smart_serve_db;
USE smart_serve_db;

-- =====================================================
-- TABLES STRUCTURE
-- =====================================================

-- Admin Users Table
CREATE TABLE IF NOT EXISTS admins (
    admin_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    role ENUM('super_admin', 'admin', 'kitchen_staff') DEFAULT 'admin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Categories Table
CREATE TABLE IF NOT EXISTS categories (
    category_id INT PRIMARY KEY AUTO_INCREMENT,
    category_name VARCHAR(100) NOT NULL,
    description TEXT,
    image VARCHAR(255),
    status ENUM('active', 'inactive') DEFAULT 'active',
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Menu Items Table
CREATE TABLE IF NOT EXISTS menu_items (
    item_id INT PRIMARY KEY AUTO_INCREMENT,
    category_id INT NOT NULL,
    item_name VARCHAR(150) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    image VARCHAR(255),
    is_available TINYINT(1) DEFAULT 1,
    is_vegetarian TINYINT(1) DEFAULT 0,
    is_spicy TINYINT(1) DEFAULT 0,
    preparation_time INT DEFAULT 15 COMMENT 'Time in minutes',
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Tables Table
CREATE TABLE IF NOT EXISTS tables (
    table_id INT PRIMARY KEY AUTO_INCREMENT,
    table_number VARCHAR(20) NOT NULL UNIQUE,
    seating_capacity INT NOT NULL DEFAULT 4,
    table_type ENUM('regular', 'booth', 'outdoor', 'private') DEFAULT 'regular',
    qr_code VARCHAR(255),
    status ENUM('available', 'occupied', 'reserved', 'maintenance') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Customers Table (for reservations and order tracking)
CREATE TABLE IF NOT EXISTS customers (
    customer_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100),
    phone VARCHAR(20),
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Orders Table
CREATE TABLE IF NOT EXISTS orders (
    order_id INT PRIMARY KEY AUTO_INCREMENT,
    table_id INT NOT NULL,
    customer_id INT,
    order_number VARCHAR(20) NOT NULL UNIQUE,
    order_status ENUM('pending', 'confirmed', 'preparing', 'ready', 'served', 'completed', 'cancelled') DEFAULT 'pending',
    total_amount DECIMAL(10, 2) DEFAULT 0.00,
    tax_amount DECIMAL(10, 2) DEFAULT 0.00,
    discount_amount DECIMAL(10, 2) DEFAULT 0.00,
    final_amount DECIMAL(10, 2) DEFAULT 0.00,
    special_instructions TEXT,
    order_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    prepared_time TIMESTAMP NULL,
    served_time TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (table_id) REFERENCES tables(table_id),
    FOREIGN KEY (customer_id) REFERENCES customers(customer_id)
) ENGINE=InnoDB;

-- Order Items Table
CREATE TABLE IF NOT EXISTS order_items (
    order_item_id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    item_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    unit_price DECIMAL(10, 2) NOT NULL,
    total_price DECIMAL(10, 2) NOT NULL,
    customization TEXT,
    item_status ENUM('pending', 'preparing', 'ready', 'served') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
    FOREIGN KEY (item_id) REFERENCES menu_items(item_id)
) ENGINE=InnoDB;

-- Payments Table
CREATE TABLE IF NOT EXISTS payments (
    payment_id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    payment_method ENUM('cash', 'card', 'upi', 'online') NOT NULL,
    payment_status ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'pending',
    amount DECIMAL(10, 2) NOT NULL,
    transaction_id VARCHAR(100),
    payment_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(order_id)
) ENGINE=InnoDB;

-- Reservations Table
CREATE TABLE IF NOT EXISTS reservations (
    reservation_id INT PRIMARY KEY AUTO_INCREMENT,
    customer_id INT,
    table_id INT,
    customer_name VARCHAR(100) NOT NULL,
    customer_phone VARCHAR(20) NOT NULL,
    customer_email VARCHAR(100),
    reservation_date DATE NOT NULL,
    reservation_time TIME NOT NULL,
    party_size INT NOT NULL,
    special_requests TEXT,
    status ENUM('pending', 'confirmed', 'cancelled', 'completed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(customer_id),
    FOREIGN KEY (table_id) REFERENCES tables(table_id)
) ENGINE=InnoDB;

-- Order Status History (for tracking)
CREATE TABLE IF NOT EXISTS order_status_history (
    history_id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    status VARCHAR(50) NOT NULL,
    changed_by INT,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =====================================================
-- DEFAULT DATA
-- =====================================================

-- Default Admin User (password: admin123)
INSERT INTO admins (username, password, full_name, email, role) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System Administrator', 'admin@smartserve.com', 'super_admin'),
('kitchen', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Kitchen Staff', 'kitchen@smartserve.com', 'kitchen_staff');

-- Default Categories
INSERT INTO categories (category_name, description, display_order) VALUES
('Appetizers', 'Start your meal with our delicious appetizers', 1),
('Main Course', 'Hearty main dishes to satisfy your hunger', 2),
('Beverages', 'Refreshing drinks and beverages', 3),
('Desserts', 'Sweet treats to end your meal', 4),
('Specials', 'Chef\'s special recommendations', 5);

-- Default Menu Items
INSERT INTO menu_items (category_id, item_name, description, price, is_vegetarian, is_spicy, preparation_time) VALUES
(1, 'Spring Rolls', 'Crispy vegetable spring rolls served with sweet chili sauce', 149.00, 1, 0, 10),
(1, 'Chicken Wings', 'Spicy buffalo chicken wings with blue cheese dip', 249.00, 0, 1, 15),
(1, 'Garlic Bread', 'Toasted bread with garlic butter and herbs', 99.00, 1, 0, 8),
(1, 'Soup of the Day', 'Fresh homemade soup - ask your server for today\'s selection', 129.00, 1, 0, 5),
(2, 'Grilled Chicken', 'Herb-marinated grilled chicken breast with vegetables', 349.00, 0, 0, 20),
(2, 'Butter Chicken', 'Creamy tomato-based curry with tender chicken pieces', 299.00, 0, 1, 18),
(2, 'Paneer Tikka Masala', 'Cottage cheese in rich spiced gravy', 279.00, 1, 1, 15),
(2, 'Fish and Chips', 'Beer-battered fish with crispy fries and tartar sauce', 399.00, 0, 0, 20),
(2, 'Vegetable Biryani', 'Fragrant basmati rice with mixed vegetables and spices', 249.00, 1, 1, 25),
(3, 'Fresh Lime Soda', 'Refreshing lime soda - sweet or salted', 79.00, 1, 0, 3),
(3, 'Mango Lassi', 'Creamy yogurt drink with fresh mango', 99.00, 1, 0, 5),
(3, 'Cold Coffee', 'Chilled coffee with ice cream', 129.00, 1, 0, 5),
(3, 'Masala Chai', 'Traditional Indian spiced tea', 49.00, 1, 0, 5),
(4, 'Chocolate Brownie', 'Warm chocolate brownie with vanilla ice cream', 179.00, 1, 0, 8),
(4, 'Gulab Jamun', 'Sweet milk dumplings in sugar syrup', 99.00, 1, 0, 5),
(4, 'Ice Cream Sundae', 'Three scoops of ice cream with toppings', 149.00, 1, 0, 5),
(5, 'Chef\'s Special Thali', 'Complete meal with variety of dishes', 449.00, 0, 1, 30),
(5, 'Weekend Brunch Platter', 'Assorted breakfast items - available on weekends', 399.00, 0, 0, 25);

-- Default Tables
INSERT INTO tables (table_number, seating_capacity, table_type) VALUES
('T01', 2, 'regular'),
('T02', 2, 'regular'),
('T03', 4, 'regular'),
('T04', 4, 'regular'),
('T05', 4, 'booth'),
('T06', 6, 'booth'),
('T07', 6, 'regular'),
('T08', 8, 'private'),
('T09', 4, 'outdoor'),
('T10', 4, 'outdoor');

-- =====================================================
-- INDEXES FOR PERFORMANCE
-- =====================================================
CREATE INDEX idx_orders_status ON orders(order_status);
CREATE INDEX idx_orders_table ON orders(table_id);
CREATE INDEX idx_order_items_order ON order_items(order_id);
CREATE INDEX idx_reservations_date ON reservations(reservation_date);
CREATE INDEX idx_menu_category ON menu_items(category_id);
