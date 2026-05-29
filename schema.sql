-- SEVZO Database Schema and Seed Data

CREATE DATABASE IF NOT EXISTS `sevzo` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `sevzo`;

-- 1. Users Table
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `phone` VARCHAR(15) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `wallet` DECIMAL(10, 2) DEFAULT 0.00,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 2. Products Table
CREATE TABLE IF NOT EXISTS `products` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `category` VARCHAR(100) NOT NULL,
    `price` DECIMAL(10, 2) NOT NULL,
    `image_url` TEXT,
    `images` TEXT DEFAULT NULL, -- Semicolon-separated product detail images
    `available_pincodes` VARCHAR(255) DEFAULT 'ALL',
    `brand` VARCHAR(100) DEFAULT 'SEVZO Fresh',
    `description` TEXT,
    `highlights` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 3. Addresses Table
CREATE TABLE IF NOT EXISTS `addresses` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `phone` VARCHAR(15) NOT NULL UNIQUE,
    `receiver_name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) DEFAULT NULL,
    `pincode` VARCHAR(10) NOT NULL,
    `city` VARCHAR(100) NOT NULL,
    `house_no` VARCHAR(100) NOT NULL,
    `full_address` TEXT NOT NULL,
    `landmark` VARCHAR(100) DEFAULT NULL,
    `lat` VARCHAR(50) DEFAULT NULL,
    `lng` VARCHAR(50) DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`phone`) REFERENCES `users`(`phone`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 4. Riders Table
CREATE TABLE IF NOT EXISTS `riders` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `phone` VARCHAR(15) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `is_online` TINYINT(1) DEFAULT 0,
    `lat` VARCHAR(50) DEFAULT NULL,
    `lng` VARCHAR(50) DEFAULT NULL,
    `profile_pic` LONGTEXT DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 5. Orders Table
CREATE TABLE IF NOT EXISTS `orders` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `customer_name` VARCHAR(100) NOT NULL,
    `customer_phone` VARCHAR(15) NOT NULL,
    `total_amount` DECIMAL(10, 2) NOT NULL,
    `payment_method` VARCHAR(50) NOT NULL,
    `order_status` VARCHAR(50) DEFAULT 'Pending',
    `delivery_pin` VARCHAR(10) DEFAULT NULL,
    `delivery_partner_id` INT DEFAULT NULL,
    `delivery_proof` LONGTEXT DEFAULT NULL, -- Base64 delivery proof image
    `lat` VARCHAR(50) DEFAULT NULL,
    `lng` VARCHAR(50) DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`delivery_partner_id`) REFERENCES `riders`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

-- 6. Order Items Table
CREATE TABLE IF NOT EXISTS `order_items` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `order_id` INT NOT NULL,
    `product_name` VARCHAR(255) NOT NULL,
    `quantity` INT NOT NULL,
    `price` DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;


-- --- SEED DATA ---

-- Seed Products (with multiple image links)
INSERT INTO `products` (`id`, `name`, `category`, `price`, `image_url`, `images`, `available_pincodes`, `brand`, `description`, `highlights`) VALUES
(1, 'Fresh Red Apple (Premium)', 'Fruits and Vegetables', 149.00, 
 'https://cdn-icons-png.flaticon.com/512/415/415733.png', 
 'https://images.unsplash.com/photo-1560806887-1e4cd0b6cbd6?w=400;https://images.unsplash.com/photo-1619546813926-a78fa6372cd2?w=400;https://images.unsplash.com/photo-1570913149827-d2ac84ab3f9a?w=400', 
 'ALL', 'SEVZO Organic Orchard', 'Crispy, sweet, and highly nutritious premium red apples sourced directly from Himachal orchards. Cleaned, graded, and packed under strict hygiene conditions.', 'Direct from Orchards; Rich in Antioxidants; Cleaned and Wax-free'),

(2, 'Fresh Spinach (Palak) 250g', 'Fruits and Vegetables', 29.00, 
 'https://cdn-icons-png.flaticon.com/512/1892/1892627.png', 
 'https://images.unsplash.com/photo-1576045057995-568f588f82fb?w=400;https://images.unsplash.com/photo-1589135760595-357428f67e32?w=400;https://images.unsplash.com/photo-1551893086-c03050cc068a?w=400', 
 'ALL', 'SEVZO Fresh Farms', 'Farm-fresh spinach leaves, rich in iron, fiber, and vitamins. Ideal for salads, curries, and healthy smoothies.', 'Grown Hydroponically; Rich in Iron & Fiber; Handpicked Daily'),

(3, 'Chambers Premium Basmati Rice 5kg', 'Grocery & Kitchen', 499.00, 
 'https://cdn-icons-png.flaticon.com/512/6888/6888125.png', 
 'https://images.unsplash.com/photo-1586201375761-83865001e31c?w=400;https://images.unsplash.com/photo-1596797038530-2c107229654b?w=400;https://images.unsplash.com/photo-1536304997881-a372c179924b?w=400', 
 'ALL', 'Chambers Basmati', 'Aromatic long-grain premium basmati rice. Perfect for biryani, pulao, and daily consumption. Aged for 12 months for extra fluffiness.', 'Extra Long Grain; Aged for 12 Months; Exquisite Aroma'),

(4, 'Fortune Premium Mustard Oil 1L', 'Grocery & Kitchen', 175.00, 
 'https://cdn-icons-png.flaticon.com/512/4156/4156172.png', 
 'https://images.unsplash.com/photo-1474979266404-7eaacbcd87c5?w=400;https://images.unsplash.com/photo-1614746654769-cf4d8ed2a21e?w=400;https://images.unsplash.com/photo-1544378730-8b5104b18991?w=400', 
 'ALL', 'Fortune', 'Kachi Ghani cold-pressed mustard oil with strong aroma and high pungency. Enhances taste and contains natural antioxidants.', 'Cold Pressed; High Pungency; 100% Pure Mustard Oil'),

(5, 'Amul Taaza Toned Milk 1L', 'Household Essentials', 56.00, 
 'https://cdn-icons-png.flaticon.com/512/3753/3753696.png', 
 'https://images.unsplash.com/photo-1550583724-b2692b85b150?w=400;https://images.unsplash.com/photo-1563636619-e9143da7973b?w=400;https://images.unsplash.com/photo-1628088062854-d1870b4553da?w=400', 
 'ALL', 'Amul', 'Pasteurized toned milk, homogenized for creaminess. Perfect for tea, coffee, milkshakes, and making fresh paneer.', 'Pasteurized & Toned; Homogenized; No added preservatives'),

(6, 'Premium Cashew Nuts (Kaju) 250g', 'Grocery & Kitchen', 249.00, 
 'https://cdn-icons-png.flaticon.com/512/5754/5754020.png', 
 'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=400;https://images.unsplash.com/photo-1600189020840-e9918c25266d?w=400;https://images.unsplash.com/photo-1600189020102-1de7c75628db?w=400', 
 'ALL', 'SEVZO Select', 'Crispy, creamy, and wholesome premium cashews. Hand-sorted and packed in vacuum pouches to ensure freshness.', 'Premium Quality; Double Sorted; Crunchy & Wholesome'),

(7, 'Potato Chips (Classic Salted) 80g', 'Snacks & Drinks', 30.00, 
 'https://cdn-icons-png.flaticon.com/512/2738/2738730.png', 
 'https://images.unsplash.com/photo-1566478989037-eec170784d0b?w=400;https://images.unsplash.com/photo-1613919113640-25732ec5e61f?w=400;https://images.unsplash.com/photo-1550547660-d9450f859349?w=400', 
 'ALL', 'Lays', 'Crispy potato chips made from selected farm potatoes, lightly salted. Perfect companion for movie nights and evening tea.', 'Made with Fresh Potatoes; Lightly Salted; Crispy & Fresh'),

(8, 'Dairy Milk Silk Chocolate 150g', 'Snacks & Drinks', 160.00, 
 'https://cdn-icons-png.flaticon.com/512/2405/2405597.png', 
 'https://images.unsplash.com/photo-1548907040-4d42b52115ca?w=400;https://images.unsplash.com/photo-1511381939415-e44015466834?w=400;https://images.unsplash.com/photo-1606313564200-e75d5e30476c?w=400', 
 'ALL', 'Cadbury', 'Rich, smooth, and creamy classic milk chocolate bar that melts in your mouth. Perfect for gifting and chocolate cravings.', 'Rich Milk Chocolate; Extra Smooth; Perfect for gifting'),

(9, 'Coca Cola Soft Drink 750ml', 'Snacks & Drinks', 45.00, 
 'https://cdn-icons-png.flaticon.com/512/2951/2951112.png', 
 'https://images.unsplash.com/photo-1622483767028-3f66f32aef97?w=400;https://images.unsplash.com/photo-1554866585-cd94860890b7?w=400;https://images.unsplash.com/photo-1592892111425-15e04305f961?w=400', 
 'ALL', 'Coca Cola', 'Carbonated soft drink with a refreshing, sweet, cola flavor. Best served chilled.', 'Original Taste; Serve Chilled; Refreshing Flavor'),

(10, 'Nescafé Classic Coffee 100g Jar', 'Snacks & Drinks', 320.00, 
 'https://cdn-icons-png.flaticon.com/512/1047/1047503.png', 
 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?w=400;https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=400;https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=400', 
 'ALL', 'Nescafe', '100% pure instant coffee made from selected Robusta and Arabica beans. Rich aroma and intense taste to kickstart your day.', '100% Pure Coffee; Rich Aroma; Instant Preparation'),

(11, 'Organic Tomatoes (Hybrid) 1kg', 'Fruits and Vegetables', 55.00, 
 'https://cdn-icons-png.flaticon.com/512/1135/1135537.png', 
 'https://images.unsplash.com/photo-1595855759920-86582396756a?w=400;https://images.unsplash.com/photo-1592924357228-91a4daadcfea?w=400;https://images.unsplash.com/photo-1607305387299-a3d9611cd46f?w=400', 
 'ALL', 'SEVZO Organic', 'Juicy, farm-fresh hybrid tomatoes. Handpicked at the peak of ripeness, perfect for gravies, salads, and soups.', '100% Organic; Hand-selected; Rich in Lycopene'),

(12, 'Whole Wheat Atta (Chakki Fresh) 5kg', 'Grocery & Kitchen', 260.00, 
 'https://cdn-icons-png.flaticon.com/512/3014/3014524.png', 
 'https://images.unsplash.com/photo-1574316071802-0d684efa7bf5?w=400;https://images.unsplash.com/photo-1509440159596-0249088772ff?w=400;https://images.unsplash.com/photo-1608686207856-001b95cf60ca?w=400', 
 'ALL', 'Aashirvaad', 'Chakki fresh whole wheat flour. Makes soft, fluffy rotis that retain moisture and nutrition for a longer time.', '100% MP Wheat; Chakki Ground; Highly Nutritious');

-- Seed a Default Rider
INSERT INTO `riders` (`id`, `name`, `phone`, `password`, `is_online`) VALUES
(1, 'Rajesh Kumar', '9876543210', 'rider123', 0);

-- Seed a Default Customer (with Rs 500 wallet balance)
INSERT INTO `users` (`id`, `name`, `phone`, `password`, `wallet`) VALUES
(1, 'Demo Customer', '9999999999', 'pass123', 500.00);

-- Seed Default Address for Demo Customer
INSERT INTO `addresses` (`phone`, `receiver_name`, `email`, `pincode`, `city`, `house_no`, `full_address`, `landmark`, `lat`, `lng`) VALUES
('9999999999', 'Demo Customer', 'demo@sevzo.com', '302001', 'Jaipur', 'Flat 402, Sunshine Residency', 'Near City Mall, Tonk Road, Jaipur', 'Sunshine Garden', '26.8924', '75.8073');
