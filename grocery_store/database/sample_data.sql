-- Insert categories
INSERT INTO categories (name) VALUES
('Fruits'),
('Vegetables'),
('Dairy'),
('Bakery'),
('Beverages'),
('Snacks');  -- Added new category

-- Insert products
INSERT INTO products (name, price, image, category_id) VALUES
-- Fruits
('Fresh Apples', 2.99, 'images/products/apples.svg', 1),
('Bananas', 1.99, 'images/products/bananas.svg', 1),
('Oranges', 3.49, 'images/products/oranges.svg', 1),
('Strawberries', 4.99, 'images/products/strawberries.svg', 1),

-- Vegetables
('Carrots', 1.49, 'images/products/carrots.svg', 2),
('Tomatoes', 2.99, 'images/products/tomatoes.svg', 2),
('Lettuce', 1.99, 'images/products/lettuce.svg', 2),
('Broccoli', 2.49, 'images/products/broccoli.svg', 2),

-- Dairy
('Milk', 3.99, 'images/products/milk.svg', 3),
('Cheese', 4.99, 'images/products/cheese.svg', 3),
('Yogurt', 1.99, 'images/products/yogurt.svg', 3),
('Butter', 3.49, 'images/products/butter.svg', 3),

-- Bakery
('Whole Wheat Bread', 2.99, 'images/products/bread.svg', 4),
('Croissants', 3.99, 'images/products/croissants.svg', 4),
('Muffins', 4.49, 'images/products/muffins.svg', 4),
('Bagels', 3.99, 'images/products/bagels.svg', 4),

-- Beverages
('Orange Juice', 3.99, 'images/products/orange-juice.svg', 5),
('Bottled Water', 1.49, 'images/products/water.svg', 5),
('Green Tea', 2.99, 'images/products/green-tea.svg', 5),
('Coffee', 5.99, 'images/products/coffee.svg', 5),

-- Snacks
('Potato Chips', 2.99, 'images/products/potato-chips.svg', 6),
('Mixed Nuts', 5.99, 'images/products/mixed-nuts.svg', 6),
('Chocolate Bar', 1.99, 'images/products/chocolate-bar.svg', 6),
('Popcorn', 2.49, 'images/products/popcorn.svg', 6);