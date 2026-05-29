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

-- Seed Products
INSERT INTO `products` (`id`, `name`, `category`, `price`, `image_url`, `available_pincodes`, `brand`, `description`, `highlights`) VALUES
(1, 'Fresh Red Apple (Premium)', 'Fruits and Vegetables', 149.00, 'https://cdn-icons-png.flaticon.com/512/415/415733.png', 'ALL', 'SEVZO Organic Orchard', 'Crispy, sweet, and highly nutritious premium red apples sourced directly from Himachal orchards. Cleaned, graded, and packed under strict hygiene conditions.', 'Direct from Orchards; Rich in Antioxidants; Cleaned and Wax-free'),
(2, 'Fresh Spinach (Palak) 250g', 'Fruits and Vegetables', 29.00, 'https://cdn-icons-png.flaticon.com/512/1892/1892627.png', 'ALL', 'SEVZO Fresh Farms', 'Farm-fresh spinach leaves, rich in iron, fiber, and vitamins. Ideal for salads, curries, and healthy smoothies.', 'Grown Hydroponically; Rich in Iron & Fiber; Handpicked Daily'),
(3, 'Chambers Premium Basmati Rice 5kg', 'Grocery & Kitchen', 499.00, 'https://cdn-icons-png.flaticon.com/512/6888/6888125.png', 'ALL', 'Chambers Basmati', 'Aromatic long-grain premium basmati rice. Perfect for biryani, pulao, and daily consumption. Aged for 12 months for extra fluffiness.', 'Extra Long Grain; Aged for 12 Months; Exquisite Aroma'),
(4, 'Fortune Premium Mustard Oil 1L', 'Grocery & Kitchen', 175.00, 'https://cdn-icons-png.flaticon.com/512/4156/4156172.png', 'ALL', 'Fortune', 'Kachi Ghani cold-pressed mustard oil with strong aroma and high pungency. Enhances taste and contains natural antioxidants.', 'Cold Pressed; High Pungency; 100% Pure Mustard Oil'),
(5, 'Amul Taaza Toned Milk 1L', 'Household Essentials', 56.00, 'https://cdn-icons-png.flaticon.com/512/3753/3753696.png', 'ALL', 'Amul', 'Pasteurized toned milk, homogenized for creaminess. Perfect for tea, coffee, milkshakes, and making fresh paneer.', 'Pasteurized & Toned; Homogenized; No added preservatives'),
(6, 'Premium Cashew Nuts (Kaju) 250g', 'Grocery & Kitchen', 249.00, 'https://cdn-icons-png.flaticon.com/512/5754/5754020.png', 'ALL', 'SEVZO Select', 'Crispy, creamy, and wholesome premium cashews. Hand-sorted and packed in vacuum pouches to ensure freshness.', 'Premium Quality; Double Sorted; Crunchy & Wholesome'),
(7, 'Potato Chips (Classic Salted) 80g', 'Snacks & Drinks', 30.00, 'https://cdn-icons-png.flaticon.com/512/2738/2738730.png', 'ALL', 'Lays', 'Crispy potato chips made from selected farm potatoes, lightly salted. Perfect companion for movie nights and evening tea.', 'Made with Fresh Potatoes; Lightly Salted; Crispy & Fresh'),
(8, 'Dairy Milk Silk Chocolate 150g', 'Snacks & Drinks', 160.00, 'https://cdn-icons-png.flaticon.com/512/2405/2405597.png', 'ALL', 'Cadbury', 'Rich, smooth, and creamy classic milk chocolate bar that melts in your mouth. Perfect for gifting and chocolate cravings.', 'Rich Milk Chocolate; Extra Smooth; Perfect for gifting'),
(9, 'Coca Cola Soft Drink 750ml', 'Snacks & Drinks', 45.00, 'https://cdn-icons-png.flaticon.com/512/2951/2951112.png', 'ALL', 'Coca Cola', 'Carbonated soft drink with a refreshing, sweet, cola flavor. Best served chilled.', 'Original Taste; Serve Chilled; Refreshing Flavor'),
(10, 'Nescafé Classic Coffee 100g Jar', 'Snacks & Drinks', 320.00, 'https://cdn-icons-png.flaticon.com/512/1047/1047503.png', 'ALL', 'Nescafe', '100% pure instant coffee made from selected Robusta and Arabica beans. Rich aroma and intense taste to kickstart your day.', '100% Pure Coffee; Rich Aroma; Instant Preparation'),
(11, 'Organic Tomatoes (Hybrid) 1kg', 'Fruits and Vegetables', 55.00, 'https://cdn-icons-png.flaticon.com/512/1135/1135537.png', 'ALL', 'SEVZO Organic', 'Juicy, farm-fresh hybrid tomatoes. Handpicked at the peak of ripeness, perfect for gravies, salads, and soups.', '100% Organic; Hand-selected; Rich in Lycopene'),
(12, 'Whole Wheat Atta (Chakki Fresh) 5kg', 'Grocery & Kitchen', 260.00, 'https://cdn-icons-png.flaticon.com/512/3014/3014524.png', 'ALL', 'Aashirvaad', 'Chakki fresh whole wheat flour. Makes soft, fluffy rotis that retain moisture and nutrition for a longer time.', '100% MP Wheat; Chakki Ground; Highly Nutritious');

-- Seed a Default Rider
INSERT INTO `riders` (`id`, `name`, `phone`, `password`, `is_online`) VALUES
(1, 'Rajesh Kumar', '9876543210', 'rider123', 0);

-- Seed a Default Customer (with Rs 500 wallet balance)
INSERT INTO `users` (`id`, `name`, `phone`, `password`, `wallet`) VALUES
(1, 'Demo Customer', '9999999999', 'pass123', 500.00);

-- Seed Default Address for Demo Customer
INSERT INTO `addresses` (`phone`, `receiver_name`, `email`, `pincode`, `city`, `house_no`, `full_address`, `landmark`, `lat`, `lng`) VALUES
('9999999999', 'Demo Customer', 'demo@sevzo.com', '302001', 'Jaipur', 'Flat 402, Sunshine Residency', 'Near City Mall, Tonk Road, Jaipur', 'Sunshine Garden', '26.8924', '75.8073');
