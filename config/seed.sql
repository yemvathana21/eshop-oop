-- Seed admin user (password: admin123)
INSERT INTO users (name, email, password, role) VALUES 
('System Admin', 'admin@eshop.com', '$2y$10$EVg9jzPfKLprhFbc3ho6WeRFTtY7ge8gLY4MwuzDUer4Wwku478cK', 'admin');

-- Seed customer user (password: customer123)
INSERT INTO users (name, email, password, role) VALUES 
('John Doe', 'john@gmail.com', '$2y$10$7OoPWE1OZrXsRtKQpB/jdeKEI4cmJhPrc3HeHoOgIrMXERUjY8jau', 'customer');

-- Seed products
INSERT INTO products (name, description, price, stock, image) VALUES 
('Premium Leather Backpack', 'A stylish and durable premium leather backpack suitable for daily commute and travel. Features multiple compartments and a laptop sleeve.', 79.99, 15, NULL),
('Wireless Noise-Cancelling Headphones', 'Over-ear headphones with high-fidelity sound, active noise cancellation, and up to 30 hours of battery life.', 129.50, 8, NULL),
('Minimalist Analog Watch', 'Elegant minimalist watch with Japanese quartz movement, stainless steel case, and genuine leather strap.', 49.00, 20, NULL),
('Ergonomic Office Chair', 'Adjustable lumbar support, breathable mesh back, and 3D armrests. Perfect for long work hours.', 189.99, 5, NULL),
('Mechanical Gaming Keyboard', 'Compact 80% layout keyboard with tactile mechanical switches, custom RGB backlighting, and aluminum frame.', 89.95, 12, NULL);
