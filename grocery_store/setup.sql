-- Create categories table
CREATE TABLE IF NOT EXISTS categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    image VARCHAR(255)
);

-- Create products table if not exists
CREATE TABLE IF NOT EXISTS products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    category_id INT,
    featured BOOLEAN DEFAULT 0,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- Insert sample categories
INSERT INTO categories (name, description, image) VALUES
('Vegetables', 'Fresh from local farms', 'vegetables.svg'),
('Fruits', 'Sweet & juicy selection', 'fruits.svg'),
('Dairy', 'Farm fresh dairy', 'dairy.svg'),
('Bakery', 'Freshly baked goods', 'bakery.svg'),
('Meat', 'Quality cuts of meat', 'meat.svg'),
('Beverages', 'Refreshing drinks', 'beverages.svg'),
('Snacks', 'Tasty treats', 'snacks.svg'),
('Pantry', 'Essential groceries', 'pantry.svg');

-- Insert sample products
INSERT INTO products (name, description, price, image, category_id, featured) VALUES
('Tomatoes', 'Fresh red tomatoes', 2.99, 'tomatoes.jpg', 1, 1),
('Apples', 'Crisp red apples', 3.99, 'apples.jpg', 2, 1),
('Milk', 'Fresh whole milk', 4.99, 'milk.jpg', 3, 1),
('Bread', 'Whole wheat bread', 2.99, 'bread.jpg', 4, 0),
('Chicken', 'Fresh chicken breast', 8.99, 'chicken.jpg', 5, 1);