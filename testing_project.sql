-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 26, 2025 at 11:41 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `testing_project`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`) VALUES
(1, 'Appetizers', 'Start your meal with these delicious appetizers'),
(2, 'Main Course', 'Hearty and fulfilling main course dishes'),
(3, 'Desserts', 'Sweet treats to finish your meal'),
(4, 'Beverages', 'Refreshing drinks and juices');

-- --------------------------------------------------------

--
-- Table structure for table `dietary_tags`
--

CREATE TABLE `dietary_tags` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dietary_tags`
--

INSERT INTO `dietary_tags` (`id`, `name`) VALUES
(4, 'Dairy-Free'),
(2, 'Gluten-Free'),
(1, 'Vegan'),
(3, 'Vegetarian');

-- --------------------------------------------------------

--
-- Table structure for table `menu_items`
--

CREATE TABLE `menu_items` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `is_seasonal` tinyint(1) DEFAULT 0,
  `availability_start` date DEFAULT NULL,
  `availability_end` date DEFAULT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_items`
--

INSERT INTO `menu_items` (`id`, `category_id`, `name`, `description`, `price`, `photo`, `is_seasonal`, `availability_start`, `availability_end`, `is_available`) VALUES
(1, 1, 'Bruschetta', 'Grilled bread topped with tomatoes, garlic, and basil', 6.50, 'images/bruschetta.jpg', 0, NULL, NULL, 1),
(2, 1, 'Stuffed Mushrooms', 'Mushrooms filled with cheese and herbs', 7.00, 'images/stuffed_mushrooms.jpg', 1, '2025-06-01', '2025-09-30', 1),
(3, 2, 'Margherita Pizza', 'Classic pizza with tomato, mozzarella & basil', 12.00, 'images/margherita_pizza.jpg', 0, NULL, NULL, 1),
(4, 2, 'Grilled Salmon', 'Fresh salmon with lemon butter sauce', 18.50, 'images/grilled_salmon.jpg', 0, NULL, NULL, 1),
(6, 3, 'Tiramisu', 'Classic Italian coffee-flavored dessert', 7.50, 'images/tiramisu.jpg', 0, NULL, NULL, 1),
(7, 3, 'Seasonal Fruit Tart', 'Fresh fruits on a buttery crust', 8.00, 'images/fruit_tart.jpg', 1, '2025-07-01', '2025-08-31', 1),
(8, 4, 'Fresh Lemonade', 'Homemade lemonade with mint', 4.00, 'images/lemonade.jpg', 0, NULL, NULL, 1),
(9, 4, 'Gluten-Free Iced Tea', 'Refreshing iced tea made without gluten', 4.50, 'images/iced_tea.jpg', 0, NULL, NULL, 1),
(10, 4, 'Vegan Smoothie', 'Mixed berry and almond milk smoothie', 5.00, 'images/vegan_smoothie.jpg', 0, NULL, NULL, 1),
(11, 1, 'Vegan Smoothie', 'Veegan', 6.08, '', 1, '2025-05-26', '2025-06-07', 1);

-- --------------------------------------------------------

--
-- Table structure for table `menu_item_dietary_tags`
--

CREATE TABLE `menu_item_dietary_tags` (
  `menu_item_id` int(11) NOT NULL,
  `dietary_tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_item_dietary_tags`
--

INSERT INTO `menu_item_dietary_tags` (`menu_item_id`, `dietary_tag_id`) VALUES
(1, 1),
(1, 3),
(2, 3),
(3, 3),
(7, 2),
(7, 3),
(8, 1),
(8, 2),
(8, 4),
(9, 2),
(9, 4),
(10, 1),
(10, 4);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_date` datetime NOT NULL DEFAULT current_timestamp(),
  `payment_method` varchar(50) NOT NULL,
  `address` text NOT NULL,
  `phone` varchar(20) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `order_date`, `payment_method`, `address`, `phone`, `total_amount`, `status`) VALUES
(1, 1, '2025-05-26 04:54:40', 'cash', 'sdssf', '01590007871', 20.50, 'Ready');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `menu_item_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`item_id`, `order_id`, `menu_item_id`, `name`, `price`, `quantity`) VALUES
(1, 1, 1, 'Margherita Pizza', 12.00, 1),
(2, 1, 2, 'Caesar Salad', 8.50, 1);

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `reservation_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reservation_date` date NOT NULL,
  `reservation_time` time NOT NULL,
  `guests` int(11) NOT NULL,
  `special_requests` text DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`reservation_id`, `user_id`, `reservation_date`, `reservation_time`, `guests`, `special_requests`, `status`, `created_at`) VALUES
(1, 1, '2025-05-26', '06:38:00', 2, '', 'Confirmed', '2025-05-25 23:38:56');

-- --------------------------------------------------------

--
-- Table structure for table `site_info`
--

CREATE TABLE `site_info` (
  `id` int(11) NOT NULL,
  `about_us` text NOT NULL,
  `services` text NOT NULL,
  `why_choose_us` text NOT NULL,
  `contact_email` varchar(255) NOT NULL,
  `contact_phone` varchar(50) NOT NULL,
  `contact_address` text NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `site_info`
--

INSERT INTO `site_info` (`id`, `about_us`, `services`, `why_choose_us`, `contact_email`, `contact_phone`, `contact_address`, `updated_at`) VALUES
(1, 'We are your trusted partner in managing every aspect of your restaurant efficiently and effectively.', 'Digital Menu, Table Reservations, Order Tracking, Staff Scheduling, Inventory Management, Payment Processing, Analytics & Reports.', 'Our system is designed for scalability and usability, helping restaurants of all sizes improve operations, increase revenue, and delight customers.', 'support@restaurantpro.com', '+880 1590-00787', 'Banani, Dhaka, Bangladesh', '2025-05-26 07:47:16');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `account_type` enum('user','admin') NOT NULL,
  `question` varchar(200) NOT NULL,
  `answer` varchar(100) NOT NULL,
  `balance` int(11) DEFAULT 0,
  `accepted_terms` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `account_type`, `question`, `answer`, `balance`, `accepted_terms`) VALUES
(1, 'Muktadir', 'sk@gmail.com', '1234', 'user', 'Hello', 'Hello', 0, 0),
(3, 'Hello', 'hello@gmail.com', '1111', 'admin', 'Hello', 'Hello', 0, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dietary_tags`
--
ALTER TABLE `dietary_tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `menu_item_dietary_tags`
--
ALTER TABLE `menu_item_dietary_tags`
  ADD PRIMARY KEY (`menu_item_id`,`dietary_tag_id`),
  ADD KEY `dietary_tag_id` (`dietary_tag_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`reservation_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `site_info`
--
ALTER TABLE `site_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `dietary_tags`
--
ALTER TABLE `dietary_tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `reservation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `site_info`
--
ALTER TABLE `site_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD CONSTRAINT `menu_items_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `menu_item_dietary_tags`
--
ALTER TABLE `menu_item_dietary_tags`
  ADD CONSTRAINT `menu_item_dietary_tags_ibfk_1` FOREIGN KEY (`menu_item_id`) REFERENCES `menu_items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `menu_item_dietary_tags_ibfk_2` FOREIGN KEY (`dietary_tag_id`) REFERENCES `dietary_tags` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE;

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
