-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 08, 2026 at 07:41 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `zenvy_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'admin1', 'taheerakhan10@gmail.com', '$2y$10$Oq6ukj1B7ULtzMyOMjIXseNA/iARPbZvNR/Wnk7xMk6ZxiY08xyK2', '2026-04-19 08:46:42');

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `id` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `banners`
--

INSERT INTO `banners` (`id`, `image`) VALUES
(1, 'images/1776004788_offers1.jpeg'),
(2, 'images/1776004799_offer4.jpeg'),
(3, 'images/1776004814_offer3.jpeg'),
(4, 'images/1776004826_offer2.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`id`, `user_id`, `product_id`, `product_name`, `price`, `quantity`, `image_url`) VALUES
(54, 3, 23, ' Intense Kajal Stick', 279.00, 1, 'images/1776227224_m2.jpeg'),
(55, 3, 37, ' Luxe Makeup Set', 1399.00, 2, 'images/1776232681_m16.jpeg'),
(56, 3, 34, 'Lip Gloss Shine', 349.00, 1, 'images/1776232405_m13.jpeg'),
(67, 0, 23, ' Intense Kajal Stick', 279.00, 2, 'images/1776227224_m2.jpeg'),
(70, 6, 152, 'exampl', 122.00, 1, 'images/1776540146_Screenshot 2026-04-18 192147.png'),
(73, 5, 10, 'The Midnight Noir Set', 300.00, 1, 'images/1776019549_Screenshot 2026-04-13 001254.png'),
(83, 5, 282, 'Premium Socks', 299.00, 1, 'images/1776924409_f36.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `image`) VALUES
(1, 'The Wardrobe', 'images/1776004865_1775925420_1775759956.png'),
(2, 'glow with grace', 'images/1776004893_1775925455_1775760018.png'),
(3, 'Aura of Adornments', 'images/1776004909_1775925501_1775760001.png'),
(4, 'Bags AND Carry', 'images/1776004931_1775925668_1775759928.png'),
(5, 'The Wanderlust collection', 'images/1776004946_1775925549_1775759981.png');

-- --------------------------------------------------------

--
-- Table structure for table `email_otps`
--

CREATE TABLE `email_otps` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `otp` varchar(6) NOT NULL,
  `expires_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `new_arrivals`
--

CREATE TABLE `new_arrivals` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `subcategory_name` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `new_arrivals`
--

INSERT INTO `new_arrivals` (`id`, `product_id`, `subcategory_name`, `image`) VALUES
(2, NULL, NULL, 'images/1776366232_nw.jpeg'),
(3, NULL, NULL, 'images/1776366240_nw5.jpeg'),
(4, NULL, NULL, 'images/1776366249_NW4.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` varchar(50) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `address` text NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `status` enum('Order Placed','Shipped','In Transit','Out for Delivery','Delivered') DEFAULT 'Order Placed',
  `payment_method` varchar(50) DEFAULT 'COD',
  `tracking_id` varchar(100) DEFAULT 'ZN-WAITING',
  `order_code` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `courier` varchar(50) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `try_buy` tinyint(1) DEFAULT 0,
  `try_status` varchar(50) DEFAULT 'None'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `total_amount`, `address`, `phone`, `status`, `payment_method`, `tracking_id`, `order_code`, `created_at`, `courier`, `product_name`, `try_buy`, `try_status`) VALUES
('ORD-1778166957-722', '10', 279.00, 'House No / Flat No, , National Games Village, Bengaluru - 560047', '9342208851', 'Delivered', 'COD', 'TRK69FCACAD7C46C', '', '2026-05-07 15:15:57', 'bluedart', NULL, 0, 'None'),
('ORD-1778167810-234', '10', 449.00, 'House No / Flat No, , National Games Village, Bengaluru - 560047', '9342208851', '', 'COD', 'TRK69FCB002C2ACA', '', '2026-05-07 15:30:10', 'bluedart', NULL, 0, 'None'),
('ORD-1778246960-610', '10', 211.00, 'House No / Flat No, , National Games Village, Bengaluru - 560047', '9342208851', 'Shipped', 'COD', 'TRK69FDE5307B8E1', '', '2026-05-08 13:29:20', 'bluedart', NULL, 0, 'None');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` varchar(50) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `image_url` text DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Order Placed',
  `item_status` varchar(50) DEFAULT 'Order Placed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `user_id`, `product_name`, `image_url`, `quantity`, `price`, `status`, `item_status`) VALUES
(1, 'ORD-1778166957-722', 23, 10, ' Intense Kajal Stick', 'images/1776227224_m2.jpeg', 1, 279.00, 'delivered', 'Order Placed'),
(2, 'ORD-1778167810-234', 27, 10, 'Soft Blush Palette', 'images/1776231571_m6.jpeg', 1, 449.00, 'cancelled', 'Order Placed'),
(3, 'ORD-1778246960-610', 8, 10, 'Ethereal icy blue crystal choker', 'images/1776019111_Screenshot 2026-04-13 000648.png', 1, 211.00, 'shipped', 'Order Placed');

-- --------------------------------------------------------

--
-- Table structure for table `order_tracking`
--

CREATE TABLE `order_tracking` (
  `id` int(11) NOT NULL,
  `order_id` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `razorpay_payment_id` varchar(255) DEFAULT NULL,
  `razorpay_order_id` varchar(255) DEFAULT NULL,
  `payment_method` varchar(50) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `refund_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `order_id`, `razorpay_payment_id`, `razorpay_order_id`, `payment_method`, `amount`, `status`, `refund_id`, `created_at`) VALUES
(10, 0, 'COD', NULL, 'COD', 279.00, 'cancelled', NULL, '2026-05-07 15:15:57'),
(11, 0, 'COD', NULL, 'COD', 449.00, 'cancelled', NULL, '2026-05-07 15:30:10'),
(12, 0, 'COD', NULL, 'COD', 211.00, 'cancelled', NULL, '2026-05-08 13:29:20');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(150) DEFAULT NULL,
  `category_name` varchar(100) DEFAULT NULL,
  `subcategory_name` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `old_price` decimal(10,2) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `stock` int(11) NOT NULL,
  `show_on_home` int(11) DEFAULT 1,
  `sizes` varchar(255) DEFAULT NULL,
  `discount` int(11) DEFAULT 0,
  `ml` varchar(50) DEFAULT NULL,
  `shades` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_new` tinyint(1) DEFAULT 1,
  `is_trending` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `category_name`, `subcategory_name`, `price`, `old_price`, `image`, `description`, `stock`, `show_on_home`, `sizes`, `discount`, `ml`, `shades`, `created_at`, `is_new`, `is_trending`) VALUES
(3, 'Lotus necklace ', 'Aura of Adornments', 'The Drop Of Necklaces', 349.00, 489.00, 'images/1776018292_Screenshot 2026-04-12 235334.png', 'the lotus elegance look for every day', 15, 1, '', 0, NULL, NULL, '2026-04-16 20:36:54', 0, 0),
(4, 'pink crystal heart necklace', 'Aura of Adornments', 'The Drop Of Necklaces', 199.00, 399.00, 'images/1776018473_Screenshot 2026-04-12 235528.png', 'pink crystal pendant and delicate glass pearls. This romantic coquette necklace shines with soft blush tones', 15, 0, '', 0, NULL, NULL, '2026-04-16 20:36:54', 0, 0),
(5, 'flower choker necklace', 'Aura of Adornments', 'The Drop Of Necklaces', 149.00, 200.00, 'images/1776018682_Screenshot 2026-04-12 235852.png', 'Teardrop Flower Choker Necklace, Unique Elegant, Royal Delicate Regency Choker, Pretty Fairycore Coquette Pendan', 15, 0, '', 0, NULL, NULL, '2026-04-16 20:36:54', 0, 0),
(6, 'The Emerald Heirloom', 'Aura of Adornments', 'The Drop Of Necklaces', 399.00, 699.00, 'images/1776018852_Screenshot 2026-04-13 000207.png', 'A masterpiece of timeless elegance.', 14, 0, '', 0, NULL, NULL, '2026-04-16 20:36:54', 0, 0),
(7, 'The Enchanted Blossom', 'Aura of Adornments', 'The Drop Of Necklaces', 185.00, 285.00, 'images/1776018994_Screenshot 2026-04-13 000440.png', 'It is centered by a brilliant-cut butterfly-bow pendant, creating a playful yet timeless sparkle perfect for any occasion.', 17, 0, '', 0, NULL, NULL, '2026-04-16 20:36:54', 0, 0),
(8, 'Ethereal icy blue crystal choker', 'Aura of Adornments', 'The Drop Of Necklaces', 211.00, 399.00, 'images/1776019111_Screenshot 2026-04-13 000648.png', 'Feel like royalty in this breathtaking floral crystal necklace! Featuring a row of delicate light blue glass flowers and a centerpiece heart framed in silver and gold', 13, 0, '', 0, NULL, NULL, '2026-04-16 20:36:54', 0, 0),
(9, 'The Muse Layered Suite', 'Aura of Adornments', 'The Drop Of Necklaces', 345.00, 400.00, 'images/1776019300_Screenshot 2026-04-13 000922.png', 'Master the art of the effortless layer. The Muse Suite features a curated stack of four distinct textures: a bold flat-link choker, a personalized pavé initial chain, our signature crystal butterfly, and a vintage-inspired bezel drop. Designed to be worn together for a statement look or separately for daily elegance.', 19, 0, '', 0, NULL, NULL, '2026-04-16 20:36:54', 0, 0),
(10, 'The Midnight Noir Set', 'Aura of Adornments', 'The Drop Of Necklaces', 300.00, 349.00, 'images/1776019549_Screenshot 2026-04-13 001254.png', 'Dark elegance personified. The Midnight Noir set features a gunmetal-toned collar adorned with intricate onyx-style crystals in marquise and brilliant cuts. The matching drop earrings mirror the central motif, creating a bold, gothic-glam aesthetic for your most mysterious evenings.', 19, 1, '', 0, NULL, NULL, '2026-04-16 20:36:54', 0, 0),
(11, 'The Royal Emerald Suite', 'Aura of Adornments', 'The Drop Of Necklaces', 580.00, 699.00, 'images/1776019607_Screenshot 2026-04-13 001414.png', 'A legacy in the making. This stunning four-piece suite includes a tiered brilliant-cut diamond-style necklace, drop earrings, a matching bracelet, and a statement cocktail ring.', 0, 0, '', 0, NULL, NULL, '2026-04-16 20:36:54', 0, 0),
(12, 'rose gold bangle ', 'Aura of Adornments', '✨ Bangles & Bracelets', 200.00, 259.00, 'images/1776019877_Screenshot 2026-04-13 001805.png', 'bangles for every types of functions ', 29, 0, 'free size', 0, NULL, NULL, '2026-04-16 20:36:54', 0, 0),
(13, 'pink ghungroo bangles set', 'Aura of Adornments', '✨ Bangles & Bracelets', 100.00, 149.00, 'images/1776020182_Screenshot 2026-04-13 002304.png', 'Add a soft and elegant touch to your ethnuc look with this beautiful pink ghungroo bangles set', 20, 1, '2.25 2.375 2.5 2.625 2.75', 0, NULL, NULL, '2026-04-16 20:36:54', 0, 0),
(22, 'Bold Stroke Eyeliner', 'glow with grace', 'makeup', 349.00, 499.00, 'images/1776227004_m1.jpeg', 'Precision eyeliner for sharp and defined looks.', 25, 1, '', 0, NULL, NULL, '2026-04-16 20:36:54', 0, 0),
(23, ' Intense Kajal Stick', 'glow with grace', 'makeup', 279.00, 399.00, 'images/1776227224_m2.jpeg', 'Deep black kajal for long-lasting eye definition.', 30, 1, '', 0, NULL, NULL, '2026-04-16 20:36:54', 0, 0),
(24, 'Lash Curl Mascara', 'glow with grace', 'makeup', 499.00, 699.00, 'images/1776227323_m3.jpeg', 'Adds volume and curl for fuller lashes.', 10, 0, '', 0, NULL, NULL, '2026-04-16 20:36:54', 0, 0),
(25, 'Matte Glow Foundation', 'glow with grace', 'makeup', 649.00, 899.00, 'images/1776231341_m4.jpeg', 'Smooth matte finish for a flawless skin look.', 20, 1, '', 10, '30', NULL, '2026-04-16 20:36:54', 0, 0),
(26, 'Compact Powder Pro', 'glow with grace', 'makeup', 549.00, 799.00, 'images/1776231472_m5.jpeg', 'Lightweight powder for oil control and smooth finish.', 15, 0, '', 0, '', NULL, '2026-04-16 20:36:54', 0, 0),
(27, 'Soft Blush Palette', 'glow with grace', 'makeup', 449.00, 699.00, 'images/1776231571_m6.jpeg', ' Natural blush tones for a radiant glow.', 12, 1, '', 0, '', NULL, '2026-04-16 20:36:54', 0, 0),
(28, 'Glow Highlighter Kit', 'glow with grace', 'makeup', 759.00, 999.00, 'images/1776231640_m7.jpeg', 'Shimmering shades for a luminous finish.', 10, 0, '', 15, '', NULL, '2026-04-16 20:36:54', 0, 0),
(29, 'Classic Matte Lipstick', 'glow with grace', 'makeup', 399.00, 599.00, 'images/1776231760_m8.jpeg', 'Rich matte color with smooth application.', 14, 0, '', 10, '', NULL, '2026-04-16 20:36:54', 0, 0),
(30, 'Nude Lipstick Set', 'glow with grace', 'makeup', 899.00, 1199.00, 'images/1776231868_m9.jpeg', 'Elegant nude shades perfect for everyday wear.\r\n', 18, 0, '', 20, '', NULL, '2026-04-16 20:36:54', 0, 0),
(31, 'Pro Eyeshadow Palette', 'glow with grace', 'makeup', 1099.00, 1499.00, 'images/1776231947_m10.jpeg', 'Versatile shades for bold and subtle looks.\r\n', 25, 1, '', 15, '', NULL, '2026-04-16 20:36:54', 0, 0),
(32, 'Glam Eyes Kit', 'glow with grace', 'makeup', 1249.00, 949.00, 'images/1776232213_m11.jpeg', 'Complete eye makeup set for stunning looks.', 11, 0, '', 10, '', NULL, '2026-04-16 20:36:54', 0, 0),
(33, 'Concealer Duo', 'glow with grace', 'makeup', 549.00, 799.00, 'images/1776232300_m12.jpeg', 'Covers imperfections for a smooth finish.', 13, 0, '', 0, '20', NULL, '2026-04-16 20:36:54', 0, 0),
(34, 'Lip Gloss Shine', 'glow with grace', 'makeup', 349.00, 499.00, 'images/1776232405_m13.jpeg', 'Glossy finish for soft and shiny lips.', 15, 0, '', 5, '', NULL, '2026-04-16 20:36:54', 0, 0),
(35, 'Liquid Lip Tint', 'glow with grace', 'makeup', 399.00, 599.00, 'images/1776232465_m14.jpeg', 'Lightweight tint with long-lasting color.', 19, 0, '', 10, '', NULL, '2026-04-16 20:36:54', 0, 0),
(36, 'Volume Mascara Pro', 'glow with grace', 'makeup', 499.00, 699.00, 'images/1776232569_m15.jpeg', 'Thickens lashes for dramatic eye makeup.', 10, 0, '', 0, '', NULL, '2026-04-16 20:36:54', 0, 0),
(37, ' Luxe Makeup Set', 'glow with grace', 'makeup', 1399.00, 1899.00, 'images/1776232681_m16.jpeg', ' Premium collection for a complete glam look.', 17, 0, '', 0, '', NULL, '2026-04-16 20:36:54', 0, 0),
(38, ' Herbal Face Serum', 'glow with grace', 'skincare', 649.00, 899.00, 'images/1776232924_s1.jpeg', 'Nourishing serum for glowing and healthy skin.', 17, 0, '', 0, '', NULL, '2026-04-16 20:36:54', 0, 0),
(39, ' Natural Care Kit', 'glow with grace', 'skincare', 949.00, 1299.00, 'images/1776233007_s2.jpeg', 'Complete skincare set for daily routine.', 15, 0, '', 10, '', NULL, '2026-04-16 20:36:54', 0, 0),
(40, 'Hydrating Body Wash', 'glow with grace', 'skincare', 549.00, 799.00, 'images/1776233077_s3.jpeg', 'Gentle body wash for soft and hydrated skin.', 20, 0, '', 15, '', NULL, '2026-04-16 20:36:54', 0, 0),
(41, 'Soft Glow Cream', 'glow with grace', 'skincare', 499.00, 699.00, 'images/1776233207_s4.jpeg', 'Daily cream for smooth and radiant skin.', 15, 0, '', 15, '50', NULL, '2026-04-16 20:36:54', 0, 0),
(45, 'Moisture Lock Cream', 'glow with grace', 'skincare', 649.00, 899.00, 'images/1776235358_s5.jpeg', 'Deep hydration cream for long-lasting moisture.', 17, 0, '', 10, '50', NULL, '2026-04-16 20:36:54', 0, 0),
(46, 'Hydration Gel', 'glow with grace', 'skincare', 749.00, 999.00, 'images/1776235461_s6.jpeg', 'Lightweight gel for instant skin hydration.', 20, 1, '', 10, '50', NULL, '2026-04-16 20:36:54', 0, 0),
(48, 'Blue Floral Dress', 'The Wardrobe', 'Casuals', 1200.00, 1800.00, 'images/1776245070_casual1.jpeg', 'Summer Breezy Floral Dress', 4, 1, 'S,M,L,XL,XXL', 0, '', NULL, '2026-04-16 20:36:54', 0, 0),
(49, 'White Cotton Top', 'The Wardrobe', 'Casuals', 800.00, 1200.00, 'images/1776245214_casual2.jpeg', 'Simple White Cotton top', 6, 1, 'S,M,L,XL,XXL', 0, '', NULL, '2026-04-16 20:36:54', 0, 0),
(50, 'Denim jacket', 'The Wardrobe', 'Casuals', 2500.00, 3500.00, 'images/1776245291_casual3.jpeg', 'Classy blue denim jacket', 1, 1, 'S,M,L,XL', 0, '', NULL, '2026-04-16 20:36:54', 0, 0),
(51, 'Stripped t shirt', 'The Wardrobe', 'Casuals', 600.00, 900.00, 'images/1776245352_casual4.jpeg', 'Cotton Stripped casual wear', 9, 0, 'S,M,L,XL,XXL', 0, '', NULL, '2026-04-16 20:36:54', 0, 0),
(52, 'Beige Trousers', 'The Wardrobe', 'Casuals', 1500.00, 2200.00, 'images/1776245435_casual5.jpeg', 'Formal Casual blend trousers', 1, 0, 'S,M,L,XL', 0, '', NULL, '2026-04-16 20:36:54', 0, 0),
(53, 'Black Jumpsuite', 'The Wardrobe', 'Casuals', 1800.00, 2500.00, 'images/1776245510_casual6.jpeg', 'Elegant Black one piece', 5, 0, 'S,M,L,XL,XXL', 0, '', NULL, '2026-04-16 20:36:54', 0, 0),
(54, 'Pink mini skirt', 'The Wardrobe', 'Casuals', 900.00, 1400.00, 'images/1776245597_casual7.jpeg', 'Cute pink High waist skirt', 1, 0, 'S,M,L,XL,XXL', 0, '', NULL, '2026-04-16 20:36:54', 0, 0),
(55, 'Gray Hoodie', 'The Wardrobe', 'Casuals', 1400.00, 2000.00, 'images/1776245653_casual8.jpeg', 'soft oversized winter hoodie', 7, 0, 'S,M,L,XL,XXL', 0, '', NULL, '2026-04-16 20:36:54', 0, 0),
(56, 'Checked shirt', 'The Wardrobe', 'Casuals', 1100.00, 1600.00, 'images/1776245721_casual9.jpeg', 'Red and black flannel shirt', 6, 0, 'S,M,L,XL,XXL', 0, '', NULL, '2026-04-16 20:36:54', 0, 0),
(57, 'Denim ', 'The Wardrobe', 'Casuals', 1300.00, 1900.00, 'images/1776245867_casual10.jpeg', 'Baggy trending jean', 9, 0, 'S,M,L,XL,XXL', 0, '', NULL, '2026-04-16 20:36:54', 0, 0),
(58, 'Silk Anarkali', 'The Wardrobe', 'ethnic wear', 4500.00, 6000.00, 'images/1776246018_ethnic1.jpeg', 'Heavy silk designer anarkali', 3, 1, 'S,M,L,XL', 0, '', NULL, '2026-04-16 20:36:54', 0, 0),
(59, 'Cotton Kurti ', 'The Wardrobe', 'ethnic wear', 700.00, 1100.00, 'images/1776246083_ethnic2.jpeg', 'Daily wear printed kurti', 5, 1, 'S,M,L,XL,XXL', 0, '', NULL, '2026-04-16 20:36:54', 0, 0),
(60, 'Palazzo Set', 'The Wardrobe', 'ethnic wear', 2200.00, 3000.00, 'images/1776246142_ethnic3.jpeg', 'Kurta with matching palazzo', 6, 0, 'S,M,L,XL,XXL', 0, '', NULL, '2026-04-16 20:36:54', 0, 0),
(61, 'Chiffon Chudidhar', 'The Wardrobe', 'ethnic wear', 400.00, 600.00, 'images/1776246260_ethnic4.jpeg', 'Soft Transparent gold border', 2, 0, 'S,M,L,XL,XXL', 0, '', NULL, '2026-04-16 20:36:54', 0, 0),
(62, 'Mirror Work Lehenga', 'The Wardrobe', 'ethnic wear', 8500.00, 12000.00, 'images/1776246360_ethnic5.jpeg', 'Bridal mirror work lehenga', 6, 0, '', 0, '', NULL, '2026-04-16 20:36:54', 0, 0),
(63, 'Sharara Suite', 'The Wardrobe', 'ethnic wear', 3200.00, 4500.00, 'images/1776246435_ethnic6.jpeg', 'Pink sharara with embroidery', 3, 0, 'S,M,L,XL,XXL', 0, '', NULL, '2026-04-16 20:36:54', 0, 0),
(64, 'Blue printed suit', 'The Wardrobe', 'ethnic wear', 1800.00, 2400.00, 'images/1776246575_ethnic7.jpeg', 'Hand blocked cotton suit', 3, 0, 'S,M,L,XL,XXL', 0, '', NULL, '2026-04-16 20:36:54', 0, 0),
(65, 'Banarasi dress', 'The Wardrobe', 'ethnic wear', 1500.00, 2200.00, 'images/1776246655_ethnic8.jpeg', 'Rich silk banarasi weave', 2, 0, 'S,M,L,XL,XXL', 0, '', NULL, '2026-04-16 20:36:54', 0, 0),
(66, 'Straight Cut Kurta', 'The Wardrobe', 'ethnic wear', 900.00, 1300.00, 'images/1776246719_ethnic9.jpeg', 'Simple office wear kurta', 4, 0, 'S,M,L,XL,XXL', 0, '', NULL, '2026-04-16 20:36:54', 0, 0),
(67, 'Gold embroidery top', 'The Wardrobe', 'ethnic wear', 2000.00, 2800.00, 'images/1776246782_ethnic10.jpeg', 'Party wear ethnic tunic', 3, 1, 'S,M,L,XL,XXL', 0, '', NULL, '2026-04-16 20:36:54', 0, 0),
(68, 'Kanjeevaram saree', 'The Wardrobe', 'traditional', 9000.00, 15000.00, 'images/1776246894_trad1.jpeg', 'Authentic south indian silk', 3, 1, 'S,M,L,XL,XXL', 0, '', NULL, '2026-04-16 20:36:54', 0, 0),
(69, 'Paithani saree', 'The Wardrobe', 'traditional', 7500.00, 10000.00, 'images/1776246972_trad2.jpeg', 'Handwoven maharastrian silk', 4, 1, 'S,M,L,XL,XXL', 0, '', NULL, '2026-04-16 20:36:54', 0, 0),
(70, 'Chanderi saree', 'The Wardrobe', 'traditional', 3500.00, 5000.00, 'images/1776247059_trad3.jpeg', 'Light weight silk cotton mix', 3, 1, 'S,M,L,XL,XXL', 0, '', NULL, '2026-04-16 20:36:54', 0, 0),
(71, 'Bandhani saree', 'The Wardrobe', 'traditional', 2800.00, 4000.00, 'images/1776247141_trad4.jpeg', 'Tie-dye traditional saree', 3, 0, 'S,M,L,XL,XXL', 0, '', NULL, '2026-04-16 20:36:54', 0, 0),
(72, 'Zardosi saree', 'The Wardrobe', 'traditional', 12000.00, 18000.00, 'images/1776247195_trad5.jpeg', 'Heavy gold thread work', 3, 0, 'S,M,L,XL,XXL', 0, '', NULL, '2026-04-16 20:36:54', 0, 0),
(73, 'Linen saree', 'The Wardrobe', 'traditional', 2200.00, 3000.00, 'images/1776247247_trad6.jpeg', 'Modern traditional linen', 3, 0, 'S,M,L,XL,XXL', 0, '', NULL, '2026-04-16 20:36:54', 0, 0),
(74, 'Patola saree', 'The Wardrobe', 'traditional', 15000.00, 22000.00, 'images/1776247362_trad7.jpeg', 'Double ikat luxury silk', 2, 0, 'S,M,L,XL,XXL', 0, '', NULL, '2026-04-16 20:36:54', 0, 0),
(75, 'Georgette saree', 'The Wardrobe', 'traditional', 1800.00, 2500.00, 'images/1776247447_trad8.jpeg', 'Flowy evening wear saree', 4, 0, 'S,M,L,XL,XXL', 0, '', NULL, '2026-04-16 20:36:54', 0, 0),
(76, 'Silk blouse ', 'The Wardrobe', 'traditional', 1200.00, 1800.00, 'images/1776247520_trad9.jpeg', 'Stitched brocade blouse', 3, 0, 'XL,XXL', 0, '', NULL, '2026-04-16 20:36:54', 0, 0),
(77, 'net saree', 'The Wardrobe', 'traditional', 2600.00, 3800.00, 'images/1776247573_trad10.jpeg', 'transparent party wear saree', 5, 0, 'S,M,L,XL,XXL', 0, '', NULL, '2026-04-16 20:36:54', 0, 0),
(78, ' Vitamin C Serum', 'glow with grace', 'skincare', 749.00, 999.00, 'images/1776322844_s9.jpeg', ' Brightens skin and improves texture.', 16, 0, '', 0, '30', NULL, '2026-04-16 20:36:54', 0, 0),
(79, 'Eye Repair Cream', 'glow with grace', 'skincare', 579.00, 799.00, 'images/1776322956_s7.jpeg', 'Reduces dark circles and fine lines.', 10, 0, '', 0, '20', NULL, '2026-04-16 20:36:54', 0, 0),
(80, 'Glow Therapy Cream', 'glow with grace', 'skincare', 649.00, 899.00, 'images/1776323039_s8.jpeg', ' Boosts skin glow with nourishing ingredients.', 20, 0, '', 0, '50', NULL, '2026-04-16 20:36:54', 0, 0),
(81, 'Citrus Skin Elixir', 'glow with grace', 'skincare', 698.00, 874.00, 'images/1776323156_s10.jpeg', 'Refreshing citrus formula for glowing skin.', 25, 0, '', 0, '30', NULL, '2026-04-16 20:36:54', 0, 0),
(82, 'Daily Cleanser Set', 'glow with grace', 'skincare', 999.00, 1500.00, 'images/1776323250_s11.jpeg', ' Gentle cleansers for everyday skincare.', 12, 0, '', 0, '', NULL, '2026-04-16 20:36:54', 0, 0),
(83, 'Soft Wash Duo', 'glow with grace', 'skincare', 699.00, 999.00, 'images/1776323342_s12.jpeg', ' Mild wash for smooth and clean skin.', 14, 0, '', 0, '200', NULL, '2026-04-16 20:36:54', 0, 0),
(84, ' Orange Glow Cleanser', 'glow with grace', 'skincare', 499.00, 675.00, 'images/1776323441_s13.jpeg', 'Brightening cleanser with citrus extracts.', 20, 1, '', 10, '100', NULL, '2026-04-16 20:36:54', 0, 0),
(85, 'Foam Cleanser', 'glow with grace', 'skincare', 549.00, 789.00, 'images/1776323527_s14.jpeg', 'Deep cleansing foam for fresh skin feel.', 18, 0, '', 0, '120', NULL, '2026-04-16 20:36:54', 0, 0),
(86, 'Hand Wash Clean', 'glow with grace', 'skincare', 249.00, 399.00, 'images/1776323600_s15.jpeg', 'Gentle hand wash for daily hygiene.', 17, 0, '', 0, '', NULL, '2026-04-16 20:36:54', 0, 0),
(87, 'Soft Care Lotion', 'glow with grace', 'skincare', 499.00, 699.00, 'images/1776323674_s16.jpeg', 'Moisturizing lotion for smooth skin.', 19, 1, '', 0, '100', NULL, '2026-04-16 20:36:54', 0, 0),
(88, 'Pure Drop Serum ', 'glow with grace', 'skincare', 659.00, 859.00, 'images/1776323793_s17.jpeg', 'Lightweight serum for daily skin nourishment.', 23, 1, '', 0, '100', NULL, '2026-04-16 20:36:54', 0, 0),
(89, 'Aloe Vera Gel', 'glow with grace', 'skincare', 399.00, 569.00, 'images/1776323871_s18.jpeg', 'Soothing gel for hydration and skin relief.', 16, 0, '', 0, '100', NULL, '2026-04-16 20:36:54', 0, 0),
(90, 'Clay Mask Therapy', 'glow with grace', 'skincare', 549.00, 799.00, 'images/1776323957_s19.jpeg', 'Detoxifying mask for clear and smooth skin.', 14, 0, '', 0, '50', NULL, '2026-04-16 20:36:54', 0, 0),
(91, ' Hydration Cream Pro', 'glow with grace', 'skincare', 649.00, 899.00, 'images/1776324054_s21.jpeg', 'Hydration Cream Pro', 21, 1, '', 0, '50', NULL, '2026-04-16 20:36:54', 0, 0),
(93, 'Gua Sha with facial rollers', 'glow with grace', 'skincare', 349.00, 699.00, 'images/1776324405_s20.jpeg', 'Refreshing skincare tool for facial massage.', 10, 1, '', 0, '', NULL, '2026-04-16 20:36:54', 0, 0),
(95, 'Comfortable Pant', 'The Wardrobe', 'sportswear\'s ', 1200.00, 1800.00, 'images/1776329933_sport1.jpeg', 'High waisted stretchable pant', 9, 0, 'S,M,L,XL,XXL', 0, '', NULL, '2026-04-16 20:36:54', 0, 0),
(96, 'Dry fit T-shirt', 'The Wardrobe', 'sportswear\'s ', 900.00, 1400.00, 'images/1776330250_sport2.jpeg', 'Moisture wicking gym shirt', 8, 1, 'S,M,L,XL,XXL', 0, '', NULL, '2026-04-16 20:36:54', 0, 0),
(97, 'Sports wear', 'The Wardrobe', 'sportswear\'s ', 1100.00, 1600.00, 'images/1776330389_sport3.jpeg', 'High impact support wear', 5, 1, 'S,M,L,XL,XXL', 0, '', NULL, '2026-04-16 20:36:54', 0, 0),
(98, 'Running shorts', 'The Wardrobe', 'sportswear\'s ', 700.00, 1100.00, 'images/1776330451_sport4.jpeg', 'Light weight shorts', 7, 1, 'S,M,L,XL,XXL', 0, '', NULL, '2026-04-16 20:36:54', 0, 0),
(99, 'Track suit set', 'The Wardrobe', 'sportswear\'s ', 3500.00, 4000.00, 'images/1776330524_sport5.jpeg', 'Full zipper jacket and pants ', 7, 0, 'S,M,L,XL,XXL', 0, '', NULL, '2026-04-16 20:36:54', 0, 0),
(100, 'Cycling shorts', 'The Wardrobe', 'sportswear\'s ', 800.00, 1200.00, 'images/1776330603_sport6.jpeg', 'Cycling comport wear', 7, 0, 'S,M,L,XL,XXL', 0, '', NULL, '2026-04-16 20:36:54', 0, 0),
(101, 'Windbreaker', 'The Wardrobe', 'sportswear\'s ', 2200.00, 3000.00, 'images/1776330725_sport7.jpeg', 'Water resistent outdoor jacket', 4, 0, 'S,M,L,XL,XXL', 0, '', NULL, '2026-04-16 20:36:54', 0, 0),
(102, 'Gym vest', 'The Wardrobe', 'sportswear\'s ', 600.00, 900.00, 'images/1776330786_sport8.jpeg', 'Cotton rib tank top', 6, 0, 'S,M,L,XL,XXL', 0, '', NULL, '2026-04-16 20:36:54', 0, 0),
(103, 'Compression tights', 'The Wardrobe', 'sportwear\'s ', 1800.00, 2500.00, 'images/1776330865_sport9.jpeg', 'Muscles recovery sports wear', 4, 0, 'S,M,L,XL,XXL', 0, '', NULL, '2026-04-16 20:36:54', 0, 0),
(104, 'Tennis skirt', 'The Wardrobe', 'sportswear\'s ', 1300.00, 1900.00, 'images/1776330945_sport10.jpeg', 'Active wear skirt with shorts', 4, 0, 'S,M,L,XL,XXL', 0, '', NULL, '2026-04-16 20:36:54', 0, 0),
(105, 'Red zardosi lehenga', 'The Wardrobe', 'Bridal collection ', 25000.00, 30000.00, 'images/1776331142_bridal1.jpeg', 'Exquisite red silk lehenga with heavy gold zardosi work', 3, 1, 'S,M,L,XL,XXL', 0, '', NULL, '2026-04-16 20:36:54', 0, 0),
(106, 'Golden bridal saree', 'The Wardrobe', 'Bridal collection ', 18000.00, 25000.00, 'images/1776331215_bridal2.jpeg', 'Shimmering golden tissue saree for wedding receptions ', 5, 1, '', 0, '', NULL, '2026-04-16 20:36:54', 0, 0),
(107, 'Velvet maroon lehenga', 'The Wardrobe', 'Bridal collection ', 32000.00, 40000.00, 'images/1776331279_bridal3.jpeg', 'Royal maroon velvet lehenga with diamond stone work', 4, 0, '', 0, '', NULL, '2026-04-16 20:36:54', 0, 0),
(108, 'White christian gown', 'The Wardrobe', 'Bridal collection ', 15000.00, 28000.00, 'images/1776331345_bridal4.jpeg', 'Elegant lace wedding gown', 5, 1, 'S,M,L,XL', 0, '', NULL, '2026-04-16 20:36:54', 0, 0),
(109, 'Pink floral lehenga', 'The Wardrobe', 'Bridal collection ', 12000.00, 20000.00, 'images/1776331405_bridal5.jpeg', 'Modern floral print lehenga for ceremony', 18, 0, 'S,M,L,XL', 0, '', '', '2026-04-16 20:36:54', 0, 0),
(110, 'Heavy banarasi saree', 'The Wardrobe', 'Bridal collection ', 9500.00, 15000.00, 'images/1776331460_bridal6.jpeg', 'Authentic handwoven banarasi silk ', 6, 0, '', 0, '', NULL, '2026-04-16 20:36:54', 0, 0),
(111, 'Designer net ghagra', 'The Wardrobe', 'Bridal collection ', 14000.00, 20000.00, 'images/1776331526_bridal7.jpeg', 'Sky blue net ghagra with silver thread embroidary', 4, 0, '', 0, '', NULL, '2026-04-16 20:36:54', 0, 0),
(112, 'Ivory wedding saree', 'The Wardrobe', 'Bridal collection ', 11000.00, 19000.00, 'images/1776331588_bridal8.jpeg', 'Sophisticated ivory saree with pear border', 4, 0, '', 0, '', NULL, '2026-04-16 20:36:54', 0, 0),
(113, 'Peacock blue bridal saree', 'The Wardrobe', 'Bridal collection ', 21000.00, 25000.00, 'images/1776331665_bridal9.jpeg', 'Stunning peacock blue silk with traditional motifs', 6, 0, '', 0, '', NULL, '2026-04-16 20:36:54', 0, 0),
(114, 'Rose gold reception gown', 'The Wardrobe', 'Bridal collection ', 17000.00, 26000.00, 'images/1776331754_bridal10.jpeg', 'Modern rose gold sequin gown for bridal parties', 4, 1, 'S,M,L,XL', 0, '', NULL, '2026-04-16 20:36:54', 0, 0),
(115, 'Leather Handbag ', 'Bags AND Carry', 'HANDBAG\'S ', 1299.00, 1599.00, 'images/1776333714_b1.jpeg', 'Premium leather Handbag', 6, 1, '', 0, '', NULL, '2026-04-16 20:36:54', 0, 0),
(116, 'Leather Handbag', 'The Wardrobe', 'HANDBAG\'S ', 1399.00, 1899.00, 'images/1776333800_b2.jpeg', 'Stylish Leather Handbags', 6, 1, '', 0, '', NULL, '2026-04-16 20:36:54', 0, 0),
(117, 'Classic Handbags', 'The Wardrobe', 'HANDBAG\'S ', 999.00, 1499.00, 'images/1776333853_b3.jpeg', 'Elegant Classic Handbags', 8, 1, '', 0, '', NULL, '2026-04-16 20:36:54', 0, 0),
(118, 'Office handbags', 'The Wardrobe', 'HANDBAG\'S ', 1499.00, 1999.00, 'images/1776333904_b4.jpeg', 'Perfect Office handbags', 8, 0, '', 0, '', NULL, '2026-04-16 20:36:54', 0, 0),
(119, 'Mini handbags', 'The Wardrobe', 'HANDBAG\'S ', 899.00, 1299.00, 'images/1776333946_b5.jpeg', 'Compact stylish bag', 6, 0, '', 0, '', NULL, '2026-04-16 20:36:54', 0, 0),
(120, 'Designer Handbag ', 'The Wardrobe', 'HANDBAG\'S ', 1799.00, 2499.00, 'images/1776334013_b6.jpeg', 'Premium designer bag', 6, 0, '', 0, '', NULL, '2026-04-16 20:36:54', 0, 0),
(121, 'Party handbag', 'The Wardrobe', 'HANDBAG\'S ', 1599.00, 2099.00, 'images/1776334070_b7.jpeg', 'Perfect for parties', 5, 0, '', 0, '', NULL, '2026-04-16 20:36:54', 0, 0),
(122, 'Trendy handbag', 'The Wardrobe', 'HANDBAG\'S ', 1199.00, 1699.00, 'images/1776334140_b8.jpeg', 'Modern trendy bag', 9, 0, '', 0, '', NULL, '2026-04-16 20:36:54', 0, 0),
(123, 'Chain handbag', 'The Wardrobe', 'HANDBAG\'S ', 1099.00, 1599.00, 'images/1776334226_b9.jpeg', 'Chain style bag', 10, 1, '', 0, '', NULL, '2026-04-16 20:36:54', 0, 0),
(124, 'Luxury Handbag', 'The Wardrobe', 'HANDBAG\'S ', 1899.00, 2299.00, 'images/1776334275_b10.jpeg', 'Luxury bag for girls', 12, 0, '', 0, '', NULL, '2026-04-16 20:36:54', 0, 0),
(127, 'Glow Sheet Mask', 'glow with grace', 'skincare', 399.00, 599.00, 'images/1776417843_s23.jpeg', 'Brightening mask for fresh radiant skin.', 12, 0, '', 0, '', NULL, '2026-04-17 09:24:03', 0, 0),
(128, 'Root Revival Oil', 'glow with grace', 'haircare', 549.00, 799.00, 'images/1776418150_h1.jpeg', 'Nourishes scalp deeply and promotes healthy hair growth.', 15, 0, '', 0, '100', NULL, '2026-04-17 09:29:10', 0, 0),
(129, 'herbal shine kit', 'glow with grace', 'haircare', 849.00, 1199.00, 'images/1776418305_h2.jpeg', 'a complete herbal blend for smooth and shiny hair', 15, 0, '', 0, '100', NULL, '2026-04-17 09:31:45', 0, 0),
(130, 'natural care duo', 'glow with grace', 'haircare', 899.00, 1299.00, 'images/1776418391_h3.jpeg', 'gentle natural formula for everyday haircare routine ', 12, 0, '', 0, '100', NULL, '2026-04-17 09:33:11', 0, 0),
(131, 'smooth silk conditioner', 'glow with grace', 'haircare', 499.00, 699.00, 'images/1776418495_h4.jpeg', 'leaves hair soft,silky and easy to manage', 10, 0, '', 0, '200', NULL, '2026-04-17 09:34:55', 0, 0),
(132, 'botanical hair serum', 'glow with grace', 'haircare', 469.00, 899.00, 'images/1776418584_h5.jpeg', 'Lightweight serum that adds shine and reduces frizz.', 18, 0, '', 0, '50', NULL, '2026-04-17 09:36:24', 0, 0),
(133, 'Herbal Spa Essentials', 'glow with grace', 'haircare', 799.00, 1099.00, 'images/1776418697_h6.jpeg', 'Relaxing herbal care for a refreshing hair spa feel.\r\n', 13, 0, '', 0, '100', NULL, '2026-04-17 09:38:17', 0, 0),
(134, 'Daily Care Shampoo Set', 'glow with grace', 'haircare', 699.00, 999.00, 'images/1776418782_h7.jpeg', 'Perfect daily shampoo for clean and fresh hair.', 15, 0, '', 0, '200', NULL, '2026-04-17 09:39:42', 0, 0),
(135, 'Coconut Therapy Oil', 'The Wardrobe', '', 569.00, 845.00, 'images/1776418846_h12.jpeg', 'Deep conditioning oil for strong and hydrated hair.', 16, 0, '', 0, '100', NULL, '2026-04-17 09:40:46', 0, 0),
(136, 'avocado Fresh Oil', 'glow with grace', 'haircare', 349.01, 499.00, 'images/1776420222_h11.jpeg', 'Refreshing citrus blend for healthy scalp care.', 22, 1, '', 0, '75', NULL, '2026-04-17 09:41:41', 0, 0),
(137, ' Spa Relax Hair Set', 'The Wardrobe', 'haircare', 549.00, 799.00, 'images/1776420011_h18.jpeg', 'Complete spa set for deep hair nourishment.', 13, 0, '', 0, '100', NULL, '2026-04-17 09:44:02', 0, 0),
(138, 'Green Therapy Collection', 'glow with grace', 'haircare', 949.00, 1299.00, 'images/1776420298_h19.jpeg', 'Eco-friendly products for healthy and natural hair care.', 11, 0, '', 0, '100', NULL, '2026-04-17 09:46:08', 0, 0),
(139, 'Velvet Touch Mist', 'glow with grace', 'fragrance ', 899.00, 1299.00, 'images/1776419256_f1.jpeg', 'A soft and elegant scent with a warm, comforting finish.', 13, 0, '', 0, '50', NULL, '2026-04-17 09:47:36', 0, 0),
(140, 'Blush Petal', 'glow with grace', 'fragrance ', 949.00, 1399.00, 'images/1776419352_f4.jpeg', 'Sweet floral notes with a soft feminine touch.', 10, 0, '', 0, '750', NULL, '2026-04-17 09:49:12', 0, 0),
(141, 'Midnight Smoke', 'glow with grace', 'fragrance ', 999.00, 149.00, 'images/1776419403_f2.jpeg', ' Deep smoky aroma with a bold and mysterious vibe.', 12, 0, '', 0, '100', NULL, '2026-04-17 09:50:03', 0, 0),
(142, 'Amber Noir', 'glow with grace', 'fragrance ', 1199.00, 1699.00, 'images/1776419473_f7.jpeg', 'Warm amber tones blended with deep woody notes.', 11, 0, '', 0, '100', NULL, '2026-04-17 09:51:13', 0, 0),
(143, ' Dark Oud', 'glow with grace', 'fragrance ', 1299.00, 1799.00, 'images/1776419532_f8.jpeg', ' Intense oud fragrance for a bold and lasting impression.', 16, 1, '', 0, '', NULL, '2026-04-17 09:52:12', 0, 0),
(144, 'Sunset Spice', 'glow with grace', 'fragrance ', 1049.00, 1699.00, 'images/1776419602_f10.jpeg', ' Warm spicy fragrance inspired by evening sunsets.', 0, 1, '', 0, '100', NULL, '2026-04-17 09:53:22', 0, 0),
(145, 'Royal Gold Elixir', 'The Wardrobe', '', 1399.00, 1899.00, 'images/1776419691_f12.jpeg', 'Rich luxurious fragrance with a premium royal touch.', 6, 0, '', 0, '100', NULL, '2026-04-17 09:54:51', 0, 0),
(146, 'violet charm', 'glow with grace', 'fragrance ', 899.00, 1299.00, 'images/1776419760_f14.jpeg', 'Sweet violet fragrance with a charming soft finish.', 13, 1, '', 0, '50', NULL, '2026-04-17 09:56:00', 0, 0),
(147, 'citrus glow', 'glow with grace', 'fragrance ', 849.00, 1199.00, 'images/1776419851_f19.jpeg', ' Bright citrus fragrance with a vibrant energy.', 21, 1, '', 0, '75', NULL, '2026-04-17 09:57:31', 0, 0),
(148, 'Rose Repair Shampoo', 'glow with grace', 'haircare', 649.00, 899.00, 'images/1776420462_h16.jpeg', 'Infused with rose essence for soft and fragrant hair.', 12, 0, '', 0, '200', NULL, '2026-04-17 10:07:42', 0, 0),
(149, 'Wooden Hair Comb Set', 'glow with grace', 'haircare', 399.00, 599.00, 'images/1776420553_h14.jpeg', 'Reduces breakage and prevents static hair damage.', 10, 0, '', 0, '', NULL, '2026-04-17 10:09:13', 0, 0),
(150, 'Vintage Elixir', 'glow with grace', 'fragrance ', 1999.00, 2500.00, 'images/1776420692_f17.jpeg', 'Classic blend of rich notes with a timeless feel.', 10, 0, '', 0, '100', NULL, '2026-04-17 10:11:32', 0, 0),
(153, 'Elegant watch for womens ', 'Aura of Adornments', '⌚ Watches - Timepieces', 699.00, 899.00, 'images/1776774032_Screenshot 2026-04-21 174720.png', 'Luxury watches crafted for everyday elegance. Discover sophisticated designs that combine precision, beauty, and contemporary style.', 20, 0, '', 0, '', NULL, '2026-04-21 12:20:32', 0, 0),
(154, 'Luxurious Textured Dial Square Women’s Watch', 'Aura of Adornments', '⌚ Watches - Timepieces', 999.00, 1299.00, 'images/1776774452_Screenshot 2026-04-21 175202.png', 'Featuring a stunning textured dial, sleek square case, and comfortable premium strap', 20, 0, '', 0, '', NULL, '2026-04-21 12:27:32', 0, 0),
(155, 'Black elegant watch', 'Aura of Adornments', '⌚ Watches - Timepieces', 399.00, 599.00, 'images/1776775327_Screenshot 2026-04-21 181006.png', 'Designed for the modern tastemaker with elegant look ', 20, 0, '', 0, '', NULL, '2026-04-21 12:42:07', 0, 0),
(156, 'Rose gold watch', 'Aura of Adornments', '⌚ Watches - Timepieces', 399.00, 599.00, 'images/1776775841_Screenshot 2026-04-21 181417.png', 'Make time stand still with a piece that commands attention', 20, 0, '', 0, '', NULL, '2026-04-21 12:50:41', 0, 0),
(157, 'the Bold and Shimmering watch', 'Aura of Adornments', '⌚ Watches - Timepieces', 899.00, 1000.00, 'images/1776776382_Screenshot 2026-04-21 182704.png', 'This captivating set pairs an opulent, rhinestone-encrusted time-piece with a delicate, matched heart-link bracelet', 20, 0, '', 0, '', NULL, '2026-04-21 12:59:42', 0, 0),
(158, 'Black stunning watch', 'Aura of Adornments', '⌚ Watches - Timepieces', 1299.00, 2000.00, 'images/1776776669_Screenshot 2026-04-21 183119.png', 'his exquisite timepiece is designed for the woman who views her watch as her most important piece of jewelry', 20, 0, '', 0, '', NULL, '2026-04-21 13:04:29', 0, 0),
(159, 'The Pearl Royale', 'Aura of Adornments', '⌚ Watches - Timepieces', 699.00, 799.00, 'images/1776778418_Screenshot 2026-04-21 183223.png', 'legance is reimagined with the Pearl Royale—a timepiece that transcends the boundary between fine jewelry and functional art', 20, 0, '', 0, '', NULL, '2026-04-21 13:33:38', 0, 0),
(160, 'Gold tassels Bracelet Watch', 'Aura of Adornments', '⌚ Watches - Timepieces', 799.00, 899.00, 'images/1776778631_Screenshot 2026-04-21 190353.png', 'Highlights the traditional bell-like charms and red stones.', 20, 0, '', 0, '', NULL, '2026-04-21 13:37:11', 0, 0),
(161, 'Ethnic bracelet watch', 'Aura of Adornments', '⌚ Watches - Timepieces', 499.00, 700.00, 'images/1776787334_Screenshot 2026-04-21 190742.png', 'we’re entering our Main Character Era with this one. This isn\'t just a watch; it’s a literal serve.', 19, 0, '', 0, '', NULL, '2026-04-21 16:02:14', 0, 0),
(162, 'The Gilded Toggle Link Watch', 'Aura of Adornments', '⌚ Watches - Timepieces', 999.00, 1399.00, 'images/1776787555_Screenshot 2026-04-21 213348.png', 'Featuring a refined rectangular case with softened corners, this watch houses a deep espresso-brown dial', 20, 0, '', 0, '', NULL, '2026-04-21 16:05:55', 0, 0),
(163, 'The Silver Filigree & Pearl Watch', 'Aura of Adornments', '⌚ Watches - Timepieces', 1699.00, 2099.00, 'images/1776787897_Screenshot 2026-04-21 213632.png', 'Step into a world of artisan craftsmanship with the Moonlit Filigree.', 20, 0, '', 0, '', NULL, '2026-04-21 16:11:37', 0, 0),
(164, 'The Emerald Tonneau Gold Watch', 'Aura of Adornments', '⌚ Watches - Timepieces', 499.00, 799.00, 'images/1776787966_Screenshot 2026-04-21 213932.png', 'Drawing inspiration from vintage high-jewelry designs', 20, 0, '', 0, '', NULL, '2026-04-21 16:12:46', 0, 0),
(165, 'Pink bali hoops earrings ', 'Aura of Adornments', 'Earrings - Studs , hoops, Drops', 199.00, 399.00, 'images/1776788175_Screenshot 2026-04-21 214440.png', 'Antique Earring Highest quality and craftsmanship. Gold plated Polki Jhumka/Indian', 20, 1, '', 0, '', NULL, '2026-04-21 16:16:15', 0, 0),
(166, 'Rhinestone Drops earrings ', 'Aura of Adornments', 'Earrings - Studs , hoops, Drops', 399.00, 550.00, 'images/1776788328_Screenshot 2026-04-21 214703.png', 'Champagne Collar Zinc Alloy Embellished Women Fashion earrings', 20, 1, '', 0, '', NULL, '2026-04-21 16:18:48', 0, 0),
(167, 'pearl dangling hoop', 'Aura of Adornments', 'Earrings - Studs , hoops, Drops', 199.00, 299.00, 'images/1776788565_Screenshot 2026-04-21 214958.png', 'Simple yet stylish pearl earrings that add elegance to any outfit!✨', 20, 0, '', 0, '', NULL, '2026-04-21 16:22:45', 0, 0),
(168, 'pink stylish earrings', 'Aura of Adornments', 'Earrings - Studs , hoops, Drops', 249.00, 349.00, 'images/1776788804_Screenshot 2026-04-21 215403.png', 'Sleek, structural, and undeniably chic earrings ', 19, 1, '', 0, '', NULL, '2026-04-21 16:26:44', 0, 0),
(169, 'festive wear earrings ', 'Aura of Adornments', 'Earrings - Studs , hoops, Drops', 499.00, 700.00, 'images/1776789050_Screenshot 2026-04-21 215721.png', 'A festive wear bali with elegant look on every festival season ', 20, 1, '', 0, '', NULL, '2026-04-21 16:30:50', 0, 0),
(170, 'Noor-E-Zenvy earrings ', 'Aura of Adornments', 'Earrings - Studs , hoops, Drops', 549.00, 849.00, 'images/1776789214_Screenshot 2026-04-21 220135.png', 'The Empress Mirror-Work Jhumka & Sahare Set', 20, 1, '', 0, '', NULL, '2026-04-21 16:33:34', 0, 0),
(171, 'humka & Triple-Tier Sahare', 'Aura of Adornments', 'Earrings - Studs , hoops, Drops', 349.00, 500.00, 'images/1776789662_Screenshot 2026-04-21 220908.png', 'Embrace the majesty of traditional craftsmanship with the Emerald Meadow set', 20, 0, '', 0, '', NULL, '2026-04-21 16:41:02', 0, 0),
(172, 'he Empress Emerald Waterfall earrings', 'Aura of Adornments', 'Earrings - Studs , hoops, Drops', 499.00, 800.00, 'images/1776790016_Screenshot 2026-04-21 221120.png', 'Bridal couture and high-fashion festive events.', 19, 1, '', 0, '', NULL, '2026-04-21 16:46:56', 0, 0),
(173, 'The Velvet Heart Bows', 'Aura of Adornments', 'Earrings - Studs , hoops, Drops', 249.00, 349.00, 'images/1776790186_Screenshot 2026-04-21 221342.png', 'Midnight Coquette Heart Drops. Where dark romance meets playful charm.', 20, 0, '', 0, '', NULL, '2026-04-21 16:49:46', 0, 0),
(174, 'The Velvet Starlight Streamers', 'Aura of Adornments', 'Earrings - Studs , hoops, Drops', 399.00, 449.00, 'images/1776790281_Screenshot 2026-04-21 221302.png', 'A soft velvet bow sits at the earlobe, giving way to cascading \"starlight\" chains encrusted with micro-crystals.', 20, 1, '', 0, '', NULL, '2026-04-21 16:51:21', 0, 0),
(175, 'The Celestial Orbit Pearls earrings ', 'Aura of Adornments', 'Earrings - Studs , hoops, Drops', 199.00, 249.00, 'images/1776792043_Screenshot 2026-04-21 221249.png', 'These dual-tier earrings feature two luminous, oversized pearls—each encased in a planetary ring of polished gold and shimmering pavé crystals.', 20, 1, '', 0, '', NULL, '2026-04-21 17:20:43', 0, 0),
(176, 'The Aurora Bow Hearts', 'Aura of Adornments', 'Earrings - Studs , hoops, Drops', 349.00, 549.00, 'images/1776792130_Screenshot 2026-04-21 221224.png', 'A glittering pavé heart stud leads to a dainty golden ribbon bow earrings.', 20, 0, '', 0, '', NULL, '2026-04-21 17:22:10', 0, 0),
(177, 'The Royal Azure Choker Set', 'Aura of Adornments', 'The Drop Of Necklaces', 1299.00, 2000.00, 'images/1776794996_Screenshot 2026-04-21 233257.png', 'This breathtaking choker features five strands of luminous white pearls, anchored by a magnificent centerpiece of emerald-cut sapphire-blue stones and intricate silver-tone filigree. Paired with matching chandelier earrings and a delicate maang tikka', 18, 1, '', 0, '', NULL, '2026-04-21 18:09:56', 0, 0),
(178, 'The Majestic Mayura Bead Set', 'Aura of Adornments', 'The Drop Of Necklaces', 1599.00, 2200.00, 'images/1776795085_Screenshot 2026-04-21 233412.png', 'Artistry meets tradition. This high-definition necklace is crafted from hundreds of micro-beads in a deep midnight blue, leading to a stunning side-pendant shaped like a diamond-encrusted peacock', 29, 0, '', 0, '', NULL, '2026-04-21 18:11:25', 0, 0),
(179, 'The Blush Blossom Gala Suite set', 'Aura of Adornments', 'The Drop Of Necklaces', 1399.00, 1999.00, 'images/1776795202_Screenshot 2026-04-21 233559.png', 'A symphony in pink. This high-jewelry suite features a wide-set collar of rose-quartz hued crystals and brilliant-cut diamond stimulants.Complete with a matching bracelet, ring, and drop earrings, it is the ultimate romantic accessory.', 20, 0, '', 0, '', NULL, '2026-04-21 18:13:22', 0, 0),
(180, 'The Vintage Violet Heritage Set', 'Aura of Adornments', 'The Drop Of Necklaces', 1259.00, 1549.00, 'images/1776795308_Screenshot 2026-04-21 233633.png', 'Featuring cushion-cut amethyst stones set against a backdrop of antique-silver floral metalwork, this set offers a sophisticated \"old-world\" charm', 28, 0, '', 0, '', NULL, '2026-04-21 18:15:08', 0, 0),
(181, 'The Mariposa Golden Set', 'Aura of Adornments', 'The Drop Of Necklaces', 999.00, 1399.00, 'images/1776795376_Screenshot 2026-04-21 233737.png', 'Everyday luxury, brunch dates, or gifting.', 30, 0, '', 0, '', NULL, '2026-04-21 18:16:16', 0, 0),
(182, 'The Heritage Emerald Gala ring', 'Aura of Adornments', '💍 Rings and hair essentials', 499.00, 699.00, 'images/1776796725_Screenshot 2026-04-22 000115.png', 'Bridal / Festive ring', 20, 0, '', 0, '', NULL, '2026-04-21 18:38:45', 0, 0),
(183, 'The Crimson Bloom Dainty ring', 'Aura of Adornments', '💍 Rings and hair essentials', 599.00, 899.00, 'images/1776796857_Screenshot 2026-04-22 000155.png', 'Occasion Wear / Gift Collection', 20, 0, '', 0, 'free size', NULL, '2026-04-21 18:40:57', 0, 0),
(184, 'dual-peacock ring', 'Aura of Adornments', '💍 Rings and hair essentials', 500.00, 600.00, 'images/1776796999_Screenshot 2026-04-22 000229.png', 'The base is finished with a rhythmic row of high-luster white pearls, adding a soft, classic touch to this bold statement piece.', 20, 0, '', 0, 'free size', NULL, '2026-04-21 18:43:19', 0, 0),
(185, 'The Heritage Emerald Cocktail Ring', 'Aura of Adornments', '💍 Rings and hair essentials', 450.00, 650.00, 'images/1776797189_Screenshot 2026-04-22 000427.png', 'Make a statement that echoes the grandeur of royalty', 20, 0, '', 0, 'free size', NULL, '2026-04-21 18:46:29', 0, 0),
(186, 'The Crimson Petal Polki Ring', 'Aura of Adornments', '💍 Rings and hair essentials', 350.00, 550.00, 'images/1776797271_Screenshot 2026-04-22 000446.png', 'The \"Polki\" finish gives it an authentic heirloom appearance, making it the perfect companion for festive hand-wear or as a standalone piece of ethnic art.', 20, 0, '', 0, 'free size', NULL, '2026-04-21 18:47:51', 0, 0),
(187, 'The Sovereign Maroon Bridal Ring', 'Aura of Adornments', '💍 Rings and hair essentials', 550.00, 850.00, 'images/1776797417_Screenshot 2026-04-22 002007.png', 'Designed for the grandest of celebrations. This heavy-set bridal ring showcases a deep maroon stone in a raised, \"crown\" setting.', 20, 0, '', 0, 'free size', NULL, '2026-04-21 18:50:17', 0, 0),
(188, 'The Velvet Heirloom Bow', 'Aura of Adornments', '💍 Rings and hair essentials', 249.00, 459.00, 'images/1776797908_5.jpeg', 'Soft, romantic, and timeless. This oversized hair bow is crafted from premium, high-density black velvet that catches the light', 18, 1, '', 0, '', NULL, '2026-04-21 18:56:40', 0, 0),
(189, 'The Silver Petal Streamer Bow', 'Aura of Adornments', '💍 Rings and hair essentials', 290.00, 450.00, 'images/1776797999_1.jpeg', 'Elegance in motion. This architectural hair clip features a polished silver-tone floral base, accented by a central crystal.', 20, 1, '', 0, '', NULL, '2026-04-21 18:59:59', 0, 0),
(190, 'The Blush Ribbon Waterfall', 'Aura of Adornments', '💍 Rings and hair essentials', 399.00, 550.00, 'images/1776798171_3.jpeg', 'A symphony of pink and pearls. This charming clip features a soft-touch fabric bow in a delicate rose-quartz hue.', 30, 0, '', 0, '', NULL, '2026-04-21 19:01:28', 0, 0),
(191, 'The Gilded Willow Branch', 'Aura of Adornments', '💍 Rings and hair essentials', 320.00, 480.00, 'images/1776798238_4.jpeg', 'Inspired by nature’s organic beauty. This statement hair clip features a meticulously carved gold-tone branch, \"blooming\" with high-luster white pearls', 20, 1, '', 0, '', NULL, '2026-04-21 19:03:58', 0, 0),
(192, 'Butterfly hair claws clip', 'Aura of Adornments', '💍 Rings and hair essentials', 299.00, 450.00, 'images/1776798370_Screenshot 2026-04-22 003451.png', 'Carry Elegance in Your Hair! This enchanting gold butterfly hair clip adds a sophisticated touch to your style with its dangling chain featuring pearl details', 20, 1, '', 0, '', NULL, '2026-04-21 19:06:10', 0, 0),
(193, 'he Sterling Waterfall clip', 'Aura of Adornments', '💍 Rings and hair essentials', 299.00, 449.99, 'images/1776798614_Screenshot 2026-04-22 003709.png', 'Elegance meets a touch of edge. This statement hair clip features a crisp, structured fabric bow in a sleek metallic silver finish.', 30, 0, '', 0, '', NULL, '2026-04-21 19:10:14', 0, 0),
(194, 'The Aurora Flutter clip', 'Aura of Adornments', '💍 Rings and hair essentials', 199.00, 325.00, 'images/1776798675_Screenshot 2026-04-22 003811.png', 'Step into a dreamscape with this iridescent masterpiece. Inspired by the soft glow of the northern lights, this butterfly clip features holographic wings that shift color as you move', 20, 1, '', 0, '', NULL, '2026-04-21 19:11:15', 0, 0),
(195, 'The Golden Flora Cascade', 'Aura of Adornments', '💍 Rings and hair essentials', 349.00, 599.00, 'images/1776798733_Screenshot 2026-04-22 003843.png', 'A centerpiece for your hair. This luxury clip features a double-blossom gold-tone motif, intricately detailed with a high-luster pearl and crystal center.', 17, 1, '', 0, '', NULL, '2026-04-21 19:12:13', 0, 0),
(196, 'the bloomy sunglasses ', 'Aura of Adornments', '🕶️ Sunglasses - frames', 399.00, 699.00, 'images/1776799288_Screenshot 2026-04-21 213749.png', 'Bold meets intellectual. These oversized square frames feature a rich, hand-polished tortoiseshell finish that adds warmth to any face shape.', 20, 0, '', 0, '', NULL, '2026-04-21 19:21:28', 0, 0),
(197, 'The Soho Tortoise Squares', 'Aura of Adornments', '🕶️ Sunglasses - frames', 699.00, 1250.00, 'images/1776799398_Screenshot 2026-04-21 174544.png', 'Crafted with lightweight acetate and reinforced with gold-tone metal hinges, they offer a sturdy yet comfortable fit.', 15, 1, '', 0, '', NULL, '2026-04-21 19:23:18', 0, 0),
(198, 'The Parisian Cat-Eye Noir', 'Aura of Adornments', '🕶️ Sunglasses - frames', 799.00, 1300.00, 'images/1776799489_Screenshot 2026-04-21 174527.png', 'inner icon with the Parisian Cat-Eye. Designed with a subtle upswept silhouette to lift and define your features', 20, 0, '', 0, '', NULL, '2026-04-21 19:24:49', 0, 0),
(199, 'Zenvy Matte Lipstick ', 'glow with grace', '', 350.00, 499.00, 'images/1776832692_NEW.JPEG', 'A luxurious Creme finish that provides dimensional, high-intensity color with a smooth, satin sheen.', 18, 0, '', 0, '', NULL, '2026-04-22 04:38:12', 0, 1),
(200, 'Berry Sweet Crochet Bag', 'The Wardrobe', '', 899.00, 1499.00, 'images/1776833334_td1.jpeg', 'A handmade, cream-colored knitted tote bag featuring 3D strawberry accents and a cute floral charm. Perfect for casual outings and a \"cottagecore\" aesthetic.', 20, 0, '', 0, '', NULL, '2026-04-22 04:48:54', 0, 1),
(201, 'Pearl Gala Mini Bag', 'The Wardrobe', '', 999.00, 1450.00, 'images/1776833412_td2.jpeg', 'A stunning statement piece made entirely of high-shine ivory pearls. Features a sturdy pearl top handle and a detachable gold crossbody chain.', 20, 0, '', 0, '', NULL, '2026-04-22 04:50:12', 0, 1),
(202, 'Sage Ribbon Comfort Slides', 'The Wardrobe', '', 650.00, 999.00, 'images/1776833483_td3.jpeg', 'Ultra-soft, sage green platform slides featuring a large cream-colored bow. Designed with a thick \"cloud\" sole for maximum comfort and indoor/outdoor wear.', 19, 0, '', 0, '', NULL, '2026-04-22 04:51:23', 0, 1),
(204, 'Satin Pearl Bridal Block Heels', 'The Wardrobe', '', 1200.00, 1600.00, 'images/1776835002_td4.jpeg', 'Elegant champagne satin pointed-toe pumps featuring a luxurious pearl-beaded ankle strap and a comfortable block heel.', 18, 0, '', 0, '', NULL, '2026-04-22 05:16:42', 0, 1),
(205, 'Golden Ribbon & Pearl Set', 'The Wardrobe', '', 599.00, 999.00, 'images/1776835047_td5.jpeg', 'A coordinated jewelry set including a pearl strand necklace and matching earrings, both featuring textured gold-toned bow pendants with a single pearl drop.', 39, 0, '', 0, '', NULL, '2026-04-22 05:17:27', 0, 1),
(206, 'Royal Mauve Velvet Anarkali', 'The Wardrobe', '', 2699.00, 3699.00, 'images/1776835535_td10.jpeg', 'A luxurious velvet Anarkali suit featuring intricate gold zari and sequin embroidery.', 20, 0, '', 0, '', NULL, '2026-04-22 05:25:35', 0, 1),
(207, 'Rose Petal Silk Lehenga Set', 'The Wardrobe', '', 3899.00, 7899.00, 'images/1776835593_td12.jpeg', 'A stunning floral-print lehenga on premium silk, accented with hand-worked embroidery and stones', 20, 0, '', 0, '', NULL, '2026-04-22 05:26:33', 0, 1),
(208, 'Midnight Oud & Bergamot EDP', 'The Wardrobe', '', 700.00, 1200.00, 'images/1776835651_td11.jpeg', 'Notes of bold Oud wood blended with fresh Italian Bergamot and a hint of warm amber for a powerful, magnetic presence.', 28, 0, '', 0, '', NULL, '2026-04-22 05:27:31', 0, 1),
(209, 'Plated Infinity Set', 'The Wardrobe', '', 1200.00, 2000.00, 'images/1776835741_td9.jpeg', 'Designed with a sleek, modern aesthetic for the contemporary woman.', 18, 0, '', 0, '', NULL, '2026-04-22 05:29:01', 0, 1),
(210, 'Verdant Glow Bracelet Stack', 'The Wardrobe', '', 1999.00, 2699.00, 'images/1776835934_td8.jpeg', '', 20, 0, '', 0, '', NULL, '2026-04-22 05:32:14', 0, 1),
(211, 'Emerald Empress Stack', 'The Wardrobe', '', 799.00, 1299.00, 'images/1776836059_td7.jpeg', 'A luxurious 5-piece gold-toned bracelet collection featuring a mix of textured bead chains, a shimmering pavé crystal band, and a centerpiece chain accented with deep emerald-green square-cut stones.', 19, 0, '', 0, '', NULL, '2026-04-22 05:34:19', 0, 1),
(212, 'Radiant Halo Curb Bracelet', 'The Wardrobe', '', 399.00, 799.00, 'images/1776836168_td6.jpeg', 'A modern take on classic elegance, this piece features a high-polish gold-finished curb chain.', 20, 0, '', 0, '', NULL, '2026-04-22 05:36:08', 0, 1),
(213, 'Zenvy Flow Embroidered Suit', 'The Wardrobe', '', 999.00, 1499.00, 'images/1776836709_NW1.jpeg', 'A beautiful sky-blue georgette suit featuring detailed white floral threadwork and mirror accents along the neckline and hem', 20, 0, 'S,M,L,XL,XXL', 0, '', NULL, '2026-04-22 05:44:57', 1, 0),
(214, 'Retro Urban High-Top Sneakers', 'The Wardrobe', '', 1599.00, 2200.00, 'images/1776837536_N2.JPEG', 'Finished with classic flat lacing and a vintage-inspired \"stitched\" midsole look, making them perfect for both street style and casual college wear.', 20, 0, '', 0, 'free size', NULL, '2026-04-22 05:58:44', 1, 0),
(215, 'Midnight Sparkle Sequin Mini', 'The Wardrobe', '', 999.00, 1299.00, 'images/1776837683_n4.jpeg', 'Make an entrance in this high-glam black mini dress covered in shimmering sequins.', 19, 0, 'S,M,L,XL,XXL', 0, '', NULL, '2026-04-22 06:01:23', 1, 0),
(216, 'Garden Ruffle formals wear', 'The Wardrobe', '', 1200.00, 2200.00, 'images/1776837755_n3.jpeg', 'A light and airy sky blue dress featuring a delicate white floral print. Designed with feminine ruffled sleeves and a tiered skirt', 28, 0, 'S,M,L,XL', 0, '', NULL, '2026-04-22 06:02:35', 1, 0),
(217, 'Blush Blossom Tiered Mini', 'The Wardrobe', '', 2299.00, 3000.00, 'images/1776838190_Screenshot 2026-04-22 112643.png', 'mini bouquet with minimal jewelry', 17, 0, '', 0, '', NULL, '2026-04-22 06:09:50', 1, 0),
(218, 'Blossom Strap Pointed Heels', 'The Wardrobe', '', 999.00, 1299.00, 'images/1776838309_Screenshot 2026-04-22 113823.png', 'An elegant pointed-toe pump featuring a unique and feminine flower-accented ankle strap heels.', 19, 0, '', 0, 'free size', NULL, '2026-04-22 06:11:49', 1, 0),
(220, 'White Sneakers', 'The Wanderlust collection', 'Sneakers', 1499.00, 1600.00, 'images/1776839481_f7.jpeg', 'Stylish white sneakers', 18, 1, '', 0, '6,7,8,9,10,11', NULL, '2026-04-22 06:26:54', 0, 0),
(221, 'Black Sneakers', 'The Wanderlust collection', 'Sneakers', 1599.00, 1799.00, 'images/1776839588_f10.jpeg', 'Trendy black sneakers', 15, 0, '', 0, '6,7,8,9,10,11', NULL, '2026-04-22 06:33:08', 0, 0),
(222, 'Casual Sneakers', 'The Wanderlust collection', 'Sneakers', 1299.00, 1499.00, 'images/1776839680_f1.jpeg', 'Daily wear sneakers', 19, 0, '', 0, '6,7,8,9,10,11', NULL, '2026-04-22 06:34:41', 0, 0),
(224, 'Premium Sneakers', 'The Wanderlust collection', 'Sneakers', 1999.00, 2200.00, 'images/1776839918_f4.jpeg', 'Premium sneakers', 19, 1, '', 0, '6,7,8,9,10,11', NULL, '2026-04-22 06:38:38', 0, 0),
(225, 'Running Sneakers', 'The Wanderlust collection', 'Sneakers', 1499.00, 1699.00, 'images/1776840447_f2.jpeg', 'Running shoes', 17, 0, '', 0, '6,7,8,9,10,11', NULL, '2026-04-22 06:47:27', 0, 0),
(226, 'Fashion Sneakers', 'The Wanderlust collection', 'Sneakers', 1699.00, 1999.00, 'images/1776840517_f9.jpeg', 'Modern sneakers', 19, 0, '', 0, '6,7,8,9,10,11', NULL, '2026-04-22 06:48:37', 0, 0),
(228, 'Chunky Sneakers', 'The Wanderlust collection', 'Sneakers', 1899.00, 2199.00, 'images/1776840667_f5.jpeg', 'Chunky style sneakers', 18, 0, '', 0, '6,7,8,9,10,11', NULL, '2026-04-22 06:51:07', 0, 0),
(229, 'Sport Sneakers', 'The Wanderlust collection', 'Sneakers', 1799.00, 2100.00, 'images/1776840783_f6.jpeg', 'Comfort sports sneakers', 20, 0, '', 0, '6,7,8,9,10,11', NULL, '2026-04-22 06:53:03', 0, 0);
INSERT INTO `products` (`id`, `name`, `category_name`, `subcategory_name`, `price`, `old_price`, `image`, `description`, `stock`, `show_on_home`, `sizes`, `discount`, `ml`, `shades`, `created_at`, `is_new`, `is_trending`) VALUES
(230, 'Street Sneakers', 'The Wanderlust collection', 'Sneakers', 1399.00, 1699.00, 'images/1776840973_f8.jpeg', 'Street style', 19, 1, '', 0, '6,7,8,9,10,11', NULL, '2026-04-22 06:56:13', 0, 0),
(231, 'Canvas Sneakers', 'The Wanderlust collection', 'Sneakers', 999.00, 1299.00, 'images/1776841573_f3.jpeg', '', 19, 0, '', 0, '6,7,8,9,10,11', NULL, '2026-04-22 07:06:13', 0, 0),
(232, 'Party Heels', 'The Wanderlust collection', 'Heels', 1299.00, 1499.00, 'images/1776841676_f11.jpeg', '', 20, 0, '', 0, '', NULL, '2026-04-22 07:07:56', 0, 0),
(233, 'Block Heels', 'The Wanderlust collection', 'Heels', 1119.00, 1399.00, 'images/1776841753_f13.jpeg', 'Comfort block heels', 20, 0, '', 0, '6,7,8,9,10,11', NULL, '2026-04-22 07:09:13', 0, 0),
(234, 'High Heels', 'The Wardrobe', 'Heels', 1499.00, 1699.00, 'images/1776841821_f19.jpeg', 'Elegant high heels', 20, 0, '', 0, '6,7,8,9,10,11', NULL, '2026-04-22 07:10:21', 0, 0),
(235, 'Kitten Heels', 'The Wardrobe', 'Heels', 999.00, 1299.00, 'images/1776841885_f17.jpeg', 'Small heel design', 18, 0, '', 0, '6,7,8,9,10,11', NULL, '2026-04-22 07:11:25', 0, 0),
(236, 'Stiletto Heels', 'The Wardrobe', 'Heels', 1599.00, 1899.00, 'images/1776841941_f18.jpeg', 'Sharp stilettos', 15, 0, '', 0, '6,7,8,9,10,11', NULL, '2026-04-22 07:12:21', 0, 0),
(238, 'Casual Heels', 'The Wanderlust collection', 'Heels', 1099.00, 1299.00, 'images/1776842135_f12.jpeg', 'Daily Wear Heels', 19, 0, '', 0, '', NULL, '2026-04-22 07:15:35', 0, 0),
(239, 'Wedding Heels', 'The Wardrobe', 'Heels', 1799.00, 1999.00, 'images/1776842230_f16.jpeg', 'Bridal heels', 19, 0, '', 0, '', NULL, '2026-04-22 07:17:10', 0, 0),
(240, 'Office Heels', 'The Wanderlust collection', 'Heels', 1299.00, 1499.00, 'images/1776842333_f15.jpeg', 'Office wear', 18, 0, '', 0, '', NULL, '2026-04-22 07:18:53', 0, 0),
(241, 'Designer Heels', 'The Wanderlust collection', 'Heels', 1899.00, 1999.00, 'images/1776842419_f14.jpeg', 'Designer collection', 20, 0, '', 0, '', NULL, '2026-04-22 07:20:19', 0, 0),
(242, 'Classic Heels', 'The Wanderlust collection', 'Heels', 1399.00, 1599.00, 'images/1776842481_f20.jpeg', 'Classic style', 20, 0, '', 0, '', NULL, '2026-04-22 07:21:21', 0, 0),
(243, 'Casual Flats', 'The Wanderlust collection', 'Flats', 799.00, 999.00, 'images/1776842673_f21.jpeg', 'Comfort flats', 20, 0, '', 0, '6,7,8,9,10,11', NULL, '2026-04-22 07:24:33', 0, 0),
(244, 'Ballet Flats', 'The Wanderlust collection', '', 899.00, 999.00, 'images/1776842782_f22.jpeg', 'Elegant flats', 20, 0, '', 0, '6,7,8,9,10,11', NULL, '2026-04-22 07:26:22', 0, 0),
(245, 'Ethnic Flats', 'The Wanderlust collection', '', 999.00, 1299.00, 'images/1776842840_f23.jpeg', 'Ethnic wear', 15, 0, '', 0, '', NULL, '2026-04-22 07:27:20', 0, 0),
(246, 'Printed Flats', 'The Wanderlust collection', 'Flats', 699.00, 899.00, 'images/1776842905_f24.jpeg', 'Printed design', 18, 0, '', 0, '6,7,8,9,10,11', NULL, '2026-04-22 07:28:25', 0, 0),
(247, 'Daily Flats', 'The Wardrobe', 'Flats', 599.00, 699.00, 'images/1776842962_f25.jpeg', 'Daily wear', 19, 1, '', 0, '6,7,8,9,10,11', NULL, '2026-04-22 07:29:22', 0, 0),
(248, 'Office Flats', 'The Wanderlust collection', 'Flats', 899.00, 1199.00, 'images/1776843032_f26.jpeg', 'Office use', 19, 0, '', 0, '6,7,8,9,10,11', NULL, '2026-04-22 07:30:32', 0, 0),
(249, 'Fashion Flats', 'The Wardrobe', 'Flats', 999.00, 1299.00, 'images/1776843097_f27.jpeg', 'Trendy flats', 19, 0, '', 0, '6,7,8,9,10,11', NULL, '2026-04-22 07:31:37', 0, 0),
(250, 'Simple Flats', 'The Wardrobe', 'Flats', 499.00, 899.00, 'images/1776843162_f28.jpeg', 'Simple design', 20, 0, '', 0, '6,7,8,9,10,11', NULL, '2026-04-22 07:32:42', 0, 0),
(251, 'Premium Flats', 'The Wardrobe', 'Flats', 1199.00, 1299.00, 'images/1776843220_f29.jpeg', 'Premium quality', 20, 0, '', 0, '6,7,8,9,10,11', NULL, '2026-04-22 07:33:40', 0, 0),
(252, 'Comfort Flats', 'The Wanderlust collection', 'Flats', 899.00, 1199.00, 'images/1776843294_f30.jpeg', 'Soft comfort', 20, 0, '6,7,8,9,10,11', 0, '6,7,8,9,10,11', NULL, '2026-04-22 07:34:54', 0, 0),
(253, 'Imperial Radiant Hexagons glass', 'Aura of Adornments', '🕶️ Sunglasses - frames', 699.00, 999.00, 'images/1776848068_Screenshot 2026-04-21 233107.png', 'Make a bold statement with these oversized, rimless geometric lenses. Featuring a stunning gradient tint and a perimeter lined with brilliant', 20, 0, '', 0, '', '', '2026-04-22 08:54:28', 0, 0),
(254, 'Aurora Flora glasses', 'Aura of Adornments', '🕶️ Sunglasses - frames', 700.00, 1100.00, 'images/1776848202_Screenshot 2026-04-21 165837.png', 'Inspired by nature and high fashion, these sunset-pink lenses are adorned with intricate crystal floral motifs and teardrop accents.', 19, 0, '', 0, '', '', '2026-04-22 08:56:42', 0, 0),
(259, 'The Pearl Regency Luxe Frames', 'Aura of Adornments', '🕶️ Sunglasses - frames', 800.00, 1200.00, 'images/1776876736_Screenshot 2026-04-21 164731.png', 'Elegance meets edge. These deep violet gradient lenses are framed by a row of micro-crystals and anchored by cluster-pearl accents at the temples.', 19, 0, '', 0, '', '', '2026-04-22 16:52:16', 0, 0),
(261, 'Classic Crocs', 'The Wanderlust collection', 'Crocs ', 1299.00, 1499.00, 'images/1776921300_c1.jpeg', 'Comfortable classic crocs', 20, 0, '6,7,8,9,10,11', 0, '', '', '2026-04-23 05:15:00', 0, 0),
(262, 'White Crocs', 'The Wanderlust collection', 'Crocs ', 1399.00, 1599.00, 'images/1776921475_c4.jpeg', 'Stylish white crocs', 20, 0, '6,7,8,9,10,11', 0, '', '', '2026-04-23 05:17:55', 0, 0),
(263, 'Black Crocs', 'The Wanderlust collection', 'Crocs ', 1399.00, 1599.00, 'images/1776921585_c8.jpeg', 'Trendy black crocs', 20, 0, '6,7,8,9,10,11', 0, '', '', '2026-04-23 05:19:45', 0, 0),
(264, 'Printed Crocs', 'The Wanderlust collection', 'Crocs ', 1499.00, 1699.00, 'images/1776921710_c9.jpeg', '', 20, 0, '6,7,8,9,10,11', 0, '', '', '2026-04-23 05:21:50', 0, 0),
(266, 'Casual Crocs', 'The Wanderlust collection', 'Crocs ', 1199.00, 1499.00, 'images/1776922010_c2.jpeg', 'Daily wear crocs', 20, 0, '6,7,8,9,10,11', 0, '', '', '2026-04-23 05:26:50', 0, 0),
(267, 'Fashion Crocs', 'The Wanderlust collection', 'Crocs ', 1599.00, 1699.00, 'images/1776922094_c10.jpeg', 'Modern crocs', 18, 0, '6,7,8,9,10,11', 0, '', '', '2026-04-23 05:28:14', 0, 0),
(268, 'Sport Crocs', 'The Wanderlust collection', 'Crocs ', 1499.00, 1699.00, 'images/1776922257_c5.jpeg', 'Sports style crocs', 18, 0, '6,7,8,9,10,11', 0, '', '', '2026-04-23 05:30:57', 0, 0),
(270, 'Lightweight Crocs', 'The Wardrobe', 'Crocs ', 1299.00, 1499.00, 'images/1776922704_c3.jpeg', 'Lightweight crocs', 20, 0, '6,7,8,9,10,11', 0, '', '', '2026-04-23 05:38:24', 0, 0),
(271, 'Premium Crocs', 'The Wardrobe', 'Crocs ', 1799.00, 1899.00, 'images/1776922841_c6.jpeg', 'Premium quality crocs', 18, 0, '6,7,8,9,10,11', 0, '', '', '2026-04-23 05:40:41', 0, 0),
(272, 'Kids Crocs', 'The Wanderlust collection', 'Crocs ', 999.00, 1299.00, 'images/1776923037_c7.jpeg', 'Crocs for kids', 19, 0, '6,7,8', 0, '', '', '2026-04-23 05:43:57', 0, 0),
(273, 'Cotton Sock', 'The Wanderlust collection', 'Socks', 199.00, 399.00, 'images/1776923192_f40.jpeg', 'Soft cotton socks', 20, 0, 'S,M,L', 0, '', '', '2026-04-23 05:46:32', 0, 0),
(274, 'Ankle Socks', 'The Wanderlust collection', 'Socks', 149.00, 299.00, 'images/1776923325_f33.jpeg', 'Comfort ankle socks', 18, 0, 'S,M,L', 0, '', '', '2026-04-23 05:48:45', 0, 0),
(275, 'Sports Socks', 'The Wanderlust collection', 'Socks', 249.00, 349.00, 'images/1776923406_f38.jpeg', 'Breathable sports socks', 20, 0, 'S,M,L', 0, '', '', '2026-04-23 05:50:06', 0, 0),
(276, 'Wool Socks', 'The Wanderlust collection', 'Socks', 299.00, 399.00, 'images/1776923476_f39.jpeg', 'Warm wool socks', 20, 0, 'S,M,L', 0, '', '', '2026-04-23 05:51:16', 0, 0),
(277, 'Printed Socks', 'The Wanderlust collection', 'Socks', 199.00, 299.00, 'images/1776923554_f35.jpeg', 'Printed Socks', 17, 0, 'S,M,L', 0, '', '', '2026-04-23 05:52:34', 0, 0),
(278, 'Casual Socks', 'The Wanderlust collection', 'Socks', 149.00, 249.00, 'images/1776923695_f37.jpeg', 'Daily wear socks', 20, 0, 'S,M,L', 0, '', '', '2026-04-23 05:54:55', 0, 0),
(279, 'Designer Socks', 'The Wanderlust collection', 'Socks', 249.00, 299.00, 'images/1776923825_f31.jpeg', 'Designer socks', 20, 0, 'S,M,L', 0, '', '', '2026-04-23 05:57:05', 0, 0),
(280, 'Low Cut Socks', 'The Wanderlust collection', 'Socks', 129.00, 229.00, 'images/1776924183_f32.jpeg', 'Low cut socks ', 18, 0, 'S,M,L', 0, '', '', '2026-04-23 06:03:03', 0, 0),
(281, 'Gym Socks', 'The Wanderlust collection', 'Socks', 229.00, 299.00, 'images/1776924304_f34.jpeg', 'Gym wear socks', 15, 0, 'S,M,L', 0, '', '', '2026-04-23 06:05:04', 0, 0),
(282, 'Premium Socks', 'The Wardrobe', 'Socks', 299.00, 399.00, 'images/1776924409_f36.jpeg', 'Premium quality socks', 20, 0, 'S,M,L', 0, '', '', '2026-04-23 06:06:49', 0, 0),
(283, 'Leather Handbag ', 'The Wardrobe', ' HANDBAGS', 1299.00, 1799.00, 'images/1776924956_b1.jpeg', 'Premium leather handbag', 1, 1, '', 0, '', '', '2026-04-23 06:15:56', 0, 0),
(284, 'Leather Handbag ', 'The Wardrobe', ' HANDBAGS', 1399.00, 1899.00, 'images/1776925350_b2.jpeg', 'Stylish leather handbag', 5, 0, '', 0, '', '', '2026-04-23 06:22:30', 1, 1),
(286, 'Office handbag', 'Bags AND Carry', ' HANDBAGS', 1499.00, 1999.00, 'images/1776925525_b4.jpeg', 'Perfect office handbag', 13, 0, '', 0, '', '', '2026-04-23 06:25:25', 0, 0),
(287, 'Classic Handbag', 'Bags AND Carry', ' HANDBAGS', 999.00, 1499.00, 'images/1776925647_b3.jpeg', 'Elegant classic bag', 12, 0, '', 0, '', '', '2026-04-23 06:27:27', 0, 0),
(288, 'Mini Handbag', 'Bags AND Carry', ' HANDBAGS', 899.00, 1299.00, 'images/1776925735_b5.jpeg', 'Compact stylish bag   ', 3, 0, '', 0, '', '', '2026-04-23 06:28:55', 0, 0),
(289, 'Designer Handbag', 'Bags AND Carry', ' HANDBAGS', 1799.00, 2499.00, 'images/1776925850_b6.jpeg', 'Premium designer bag', 6, 0, '', 0, '', '', '2026-04-23 06:30:50', 0, 0),
(290, 'Party Handbag', 'Bags AND Carry', ' HANDBAGS', 1599.00, 2099.00, 'images/1776925920_b7.jpeg', 'Perfect for parties', 5, 0, '', 0, '', '', '2026-04-23 06:32:00', 0, 0),
(291, 'Trendy Handbag', 'Bags AND Carry', ' HANDBAGS', 1199.00, 1699.00, 'images/1776926012_b8.jpeg', 'Modern trendy bag', 4, 0, '', 0, '', '', '2026-04-23 06:33:32', 1, 0),
(292, 'Chain Handbag', 'Bags AND Carry', ' HANDBAGS', 1099.00, 1599.00, 'images/1776926077_b9.jpeg', 'Chain style handbag', 4, 0, '', 0, '', '', '2026-04-23 06:34:37', 0, 1),
(293, 'Luxury Handbag', 'Bags AND Carry', ' HANDBAGS', 1999.00, 2799.00, 'images/1776926139_b10.jpeg', 'Luxury handbag', 8, 1, '', 0, '', '', '2026-04-23 06:35:39', 0, 0),
(294, 'Casual Backpack', 'Bags AND Carry', 'Backpacks', 899.00, 1299.00, 'images/1776926220_b11.jpeg', 'Daily use backpack', 4, 0, '', 0, '', '', '2026-04-23 06:37:00', 1, 0),
(295, 'Travel Backpack', 'Bags AND Carry', 'Backpacks', 1499.00, 1999.00, 'images/1776926280_b12.jpeg', 'Spacious travel bag', 5, 1, '', 0, '', '', '2026-04-23 06:38:00', 0, 0),
(296, 'College Backpack', 'Bags AND Carry', 'Backpacks', 999.00, 1499.00, 'images/1776926345_b13.jpeg', 'Student backpack', 7, 0, '', 0, '', '', '2026-04-23 06:39:05', 1, 0),
(297, 'Laptop Backpack', 'Bags AND Carry', 'Backpacks', 1299.00, 1799.00, 'images/1776926434_b14.jpeg', 'Laptop safe bag', 8, 0, '', 0, '', '', '2026-04-23 06:40:34', 0, 0),
(298, 'Sport Backpack', 'Bags AND Carry', 'Backpacks', 1099.00, 1599.00, 'images/1776926496_b15.jpeg', 'Durable sports bag', 5, 1, '', 0, '', '', '2026-04-23 06:41:36', 0, 0),
(299, 'Mini Backpack', 'Bags AND Carry', 'Backpacks', 799.00, 1199.00, 'images/1776926550_b16.jpeg', 'Compact backpack', 8, 0, '', 0, '', '', '2026-04-23 06:42:30', 0, 0),
(300, 'Backpack', 'Bags AND Carry', 'Backpacks', 1399.00, 1899.00, 'images/1776926681_b17.jpeg', 'Water resistant bag', 9, 0, '', 0, '', '', '2026-04-23 06:44:41', 0, 0),
(301, 'Fashion Backpack', 'Bags AND Carry', 'Backpacks', 1199.00, 1699.00, 'images/1776926751_b18.jpeg', 'Trendy design', 2, 0, '', 0, '', '', '2026-04-23 06:45:51', 0, 1),
(302, 'School Backpack', 'Bags AND Carry', 'Backpacks', 899.00, 1299.00, 'images/1776926817_b19.jpeg', 'School use bag', 12, 1, '', 0, '', '', '2026-04-23 06:46:57', 0, 0),
(303, 'Hiking Backpack', 'Bags AND Carry', 'Backpacks', 1599.00, 2099.00, 'images/1776926890_b20.jpeg', 'Outdoor hiking bag', 12, 0, '', 0, '', '', '2026-04-23 06:48:10', 1, 0),
(304, 'Mini Sling Bag', 'Bags AND Carry', 'slingbags', 699.00, 999.00, 'images/1776926959_b21.jpeg', 'Stylish mini sling', 1, 1, '', 0, '', '', '2026-04-23 06:49:19', 0, 1),
(305, 'Trendy Sling Bag', 'Bags AND Carry', 'slingbags', 799.00, 1199.00, 'images/1776927025_b22.jpeg', 'Modern sling bag', 6, 0, '', 0, '', '', '2026-04-23 06:50:25', 1, 0),
(306, 'Casual Sling bag', 'Bags AND Carry', 'slingbags', 599.00, 899.00, 'images/1776927088_b23.jpeg', 'Daily use sling', 3, 1, '', 0, '', '', '2026-04-23 06:51:28', 1, 0),
(307, 'Party Sling Bag', 'Bags AND Carry', 'slingbags', 999.00, 1399.00, 'images/1776927145_b24.jpeg', 'Party wear sling', 5, 0, '', 0, '', '', '2026-04-23 06:52:25', 1, 1),
(308, 'Printed Sling bag', 'Bags AND Carry', 'slingbags', 749.00, 1099.00, 'images/1776927200_b25.jpeg', 'Printed design', 6, 0, '', 0, '', '', '2026-04-23 06:53:20', 0, 0),
(309, 'Leather Sling bag', 'Bags AND Carry', 'slingbags', 1299.00, 1799.00, 'images/1776927278_b26.jpeg', 'Leather sling', 9, 1, '', 0, '', '', '2026-04-23 06:54:38', 0, 1),
(310, 'Compact Sling bag', 'Bags AND Carry', 'slingbags', 699.00, 999.00, 'images/1776927334_b27.jpeg', 'Small compact bag', 7, 0, '', 0, '', '', '2026-04-23 06:55:34', 1, 0),
(311, 'Designer Sling bag', 'Bags AND Carry', 'slingbags', 1499.00, 1999.00, 'images/1776927387_b28.jpeg', 'Designer sling', 6, 1, '', 0, '', '', '2026-04-23 06:56:27', 0, 0),
(312, 'Crossbody Sling bag', 'Bags AND Carry', 'slingbags', 899.00, 1299.00, 'images/1776927446_b29.jpeg', 'Crossbody bag', 9, 1, '', 0, '', '', '2026-04-23 06:57:26', 1, 0),
(313, 'Fashion Sling bag', 'Bags AND Carry', 'slingbags', 999.00, 1499.00, 'images/1776927509_b30.jpeg', 'Stylish sling', 2, 0, '', 0, '', '', '2026-04-23 06:58:29', 1, 0),
(314, 'Canvas Tote bag', 'Bags AND Carry', 'totebags', 499.00, 799.00, 'images/1776927568_b31.jpeg', 'Eco-friendly tote', 11, 1, '', 0, '', '', '2026-04-23 06:59:28', 0, 1),
(315, 'Printed Tote bag', 'Bags AND Carry', 'totebags', 599.00, 899.00, 'images/1776927627_b32.jpeg', 'Trendy printed tote', 1, 0, '', 0, '', '', '2026-04-23 07:00:27', 0, 1),
(316, 'Large Tote Bag', 'Bags AND Carry', 'totebags', 799.00, 1199.00, 'images/1776927689_b33.jpeg', 'Spacious tote', 8, 1, '', 0, '', '', '2026-04-23 07:01:29', 1, 0),
(317, 'Office Tote bag', 'Bags AND Carry', 'totebags', 999.00, 1499.00, 'images/1776927757_b34.jpeg', 'Office use tote', 3, 1, '', 0, '', '', '2026-04-23 07:02:37', 1, 0),
(318, 'Shopping Tote bag', 'Bags AND Carry', 'totebags', 699.00, 999.00, 'images/1776927827_b35.jpeg', 'Shopping Tote', 9, 0, '', 0, '', '', '2026-04-23 07:03:47', 1, 1),
(319, 'Designer Tote bag', 'Bags AND Carry', 'totebags', 1299.00, 1799.00, 'images/1776927882_b36.jpeg', 'Designer tote', 2, 0, '', 0, '', '', '2026-04-23 07:04:42', 1, 0),
(320, 'Cotton Tote bag', 'Bags AND Carry', 'totebags', 499.00, 799.00, 'images/1776927941_b37.jpeg', 'Cotton tote', 5, 1, '', 0, '', '', '2026-04-23 07:05:41', 0, 1),
(321, 'Fashion Tote bag', 'Bags AND Carry', 'totebags', 899.00, 1299.00, 'images/1776928022_b38.jpeg', 'Stylish tote', 3, 1, '', 0, '', '', '2026-04-23 07:07:02', 0, 0),
(322, 'Daily Tote bag', 'Bags AND Carry', 'totebags', 599.00, 899.00, 'images/1776928123_b39.jpeg', 'Daily use bag', 9, 1, '', 0, '', '', '2026-04-23 07:08:43', 1, 1),
(323, 'Premium Tote bag', 'Bags AND Carry', 'totebags', 1499.00, 1999.00, 'images/1776928185_b40.jpeg', 'Premium tote bag', 5, 1, '', 0, '', '', '2026-04-23 07:09:45', 1, 1),
(324, 'jhumka charms bangles', 'Aura of Adornments', '✨ Bangles & Bracelets', 250.00, 350.00, 'images/1778261313_Screenshot 2026-05-08 225616.png', 'Add a beautiful traditional touch to your bridal or festive look with this Kashmiri-inspired bangle set', 20, 0, '', 0, 'free size', '', '2026-05-08 17:28:33', 0, 0),
(325, 'The Gilded Horizon set', 'Aura of Adornments', '✨ Bangles & Bracelets', 1299.00, 1899.00, 'images/1778261599_Screenshot 2026-05-08 225957.png', '(Set of 4) A curated selection of four premium gold-toned bangles, ranging from bold architectural cuffs to delicate geometric designs', 20, 0, '', 0, '', '', '2026-05-08 17:33:19', 0, 0),
(326, 'The Empress Gold Stacking Duo', 'Aura of Adornments', '✨ Bangles & Bracelets', 899.00, 1399.00, 'images/1778261914_Screenshot 2026-05-08 230536.png', 'Elevate your style with this expertly paired duo designed for the modern woman', 19, 0, '', 0, '', '', '2026-05-08 17:38:34', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `returns`
--

CREATE TABLE `returns` (
  `id` int(11) NOT NULL,
  `order_id` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `pickup_status` varchar(50) NOT NULL DEFAULT 'Pending',
  `refund_status` varchar(50) NOT NULL DEFAULT 'Pending',
  `pickup_date` varchar(100) DEFAULT NULL,
  `refund_amount` varchar(100) DEFAULT NULL,
  `refund_date` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `returns`
--

INSERT INTO `returns` (`id`, `order_id`, `user_id`, `reason`, `image`, `status`, `created_at`, `pickup_status`, `refund_status`, `pickup_date`, `refund_amount`, `refund_date`) VALUES
(1, 'ORD-1778166957-722', 10, 'iadmpoopdwm', 'return_images/1778169326_Screenshot 2026-05-07 211852.png', 'approved', '2026-05-07 15:55:26', 'Product Picked Up', 'Refunded', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `subcategories`
--

CREATE TABLE `subcategories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `category_name` varchar(100) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subcategories`
--

INSERT INTO `subcategories` (`id`, `name`, `category_name`, `image`) VALUES
(1, 'The Drop Of Necklaces', 'Aura of Adornments', 'images/1776005847_1775930624_Screenshot 2026-04-11 233330.png'),
(2, '💍 Rings and hair essentials ', 'Aura of Adornments', 'images/1776005878_1775930820_Screenshot 2026-04-11 233648.png'),
(3, 'Earrings - Studs , hoops, Drops', 'Aura of Adornments', 'images/1776005900_1775931026_Screenshot 2026-04-11 234013.png'),
(4, '🕶️ Sunglasses - frames', 'Aura of Adornments', 'images/1776005921_sunglass.jpeg'),
(5, '⌚ Watches - Timepieces', 'Aura of Adornments', 'images/1776005950_1775931561_Screenshot 2026-04-11 234909.png'),
(6, '✨ Bangles & Bracelets', 'Aura of Adornments', 'images/1776005972_1775932044_Screenshot 2026-04-11 235708.png'),
(10, 'makeup', 'glow with grace', 'images/1776226549_makeup.jpeg'),
(11, 'haircare', 'glow with grace', 'images/1776226593_haircare.jpeg'),
(13, 'fragrance ', 'glow with grace', 'images/1776226674_fragrance.jpeg'),
(16, 'totebags', 'Bags AND Carry', 'images/1776229036_totebags.jpeg'),
(18, 'skincare', 'glow with grace', 'images/1776233371_skincare.jpeg'),
(20, 'casuals', 'The Wardrobe', 'images/1776244242_casual.jpeg'),
(22, 'traditional ', 'The Wardrobe', 'images/1776244413_traditional.jpeg'),
(23, 'Sportswear', 'The Wardrobe', 'images/1776244638_sportswear.jpeg'),
(24, 'Bridal collection', 'The Wardrobe', 'images/1776244663_bridal.jpeg'),
(25, 'ethnic wear', 'The Wardrobe', 'images/1776245949_ethnic.jpeg'),
(26, 'HANDBAGS', 'Bags AND Carry', 'images/1776333294_handbags.jpeg'),
(27, 'slingbags', 'Bags AND Carry', 'images/1776333373_slingbags.jpeg'),
(29, 'Heels', 'The Wanderlust collection', 'images/1776838473_heels.jpeg'),
(30, 'sneakers', 'The Wanderlust collection', 'images/1776838498_sneakers.jpeg'),
(31, 'Crocs', 'The Wanderlust collection', 'images/1776838545_crocs.jpeg'),
(32, 'Socks', 'The Wanderlust collection', 'images/1776838607_socks.jpeg'),
(33, 'Flats', 'The Wanderlust collection', 'images/1776838630_flats.jpeg'),
(34, 'Backpacks', 'Bags AND Carry', 'images/1776928369_backpacks.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `trending_banners`
--

CREATE TABLE `trending_banners` (
  `id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trending_banners`
--

INSERT INTO `trending_banners` (`id`, `image`, `title`, `link`, `status`) VALUES
(2, 'images/1776832819_tnb.jpeg.png', NULL, NULL, 1),
(3, 'images/1776834207_tdb1.jpeg', NULL, NULL, 1),
(4, 'images/1776834213_tdb2.jpeg', NULL, NULL, 1),
(5, 'images/1776834219_tdb3.jpeg', NULL, NULL, 1),
(6, 'images/1776834226_tdb4.jpeg', NULL, NULL, 1),
(7, 'images/1776834233_tdb5.jpeg', NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `phone` varchar(10) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `pincode` int(6) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `address`, `pincode`, `city`, `state`) VALUES
(7, 'Ayat ', 'ayatshaikh9767@gmail.com', '$2y$10$00lDVS53YFYtTa/v0jozO.9NdUyVl2yzndGtl9.N7IAaXD58c/Ij6', '9036575198', NULL, NULL, NULL, NULL),
(10, 'Taheera Khan', 'taheerakhan10@gmail.com', '$2y$10$ObxyH0.YzKYJRwRaFvQmtOw5R7DGtyXBNQGaPppzTo7FEiR2xan7y', '9342208851', 'House No / Flat No, , National Games Village', 560047, 'Bengaluru', 'Karnataka'),
(11, 'mehak', 'mehakshariff34@gmail.com', '$2y$10$0owXG90RG.F/x3Lt.lDSxeS/UMvMlCMRAT4L9wNIK04569.o/cTSC', '9449630198', 'no 3 jc road Narayan swamy garden', 560047, 'Bengaluru City', 'Karnataka'),
(12, 'Falak Naaz', 'fnaaz997@gmail.com', '$2y$10$OEDalEgwasaHp76xUZa17.hPBHoOMtJm5MYwnEaOg7ZQpOTQGOrdC', '8789407760', 'al ameen clg', 560002, 'bangalore', 'karnataka');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`id`, `user_id`, `product_id`) VALUES
(7, 1, 18),
(27, 1, 151),
(28, 1, 23),
(29, 1, 6),
(37, 5, 24),
(38, 5, 25),
(40, 5, 152),
(43, 7, 213),
(46, 10, 323);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_otps`
--
ALTER TABLE `email_otps`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `new_arrivals`
--
ALTER TABLE `new_arrivals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_tracking`
--
ALTER TABLE `order_tracking`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `returns`
--
ALTER TABLE `returns`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subcategories`
--
ALTER TABLE `subcategories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trending_banners`
--
ALTER TABLE `trending_banners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `email_otps`
--
ALTER TABLE `email_otps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `new_arrivals`
--
ALTER TABLE `new_arrivals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `order_tracking`
--
ALTER TABLE `order_tracking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=327;

--
-- AUTO_INCREMENT for table `returns`
--
ALTER TABLE `returns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `subcategories`
--
ALTER TABLE `subcategories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `trending_banners`
--
ALTER TABLE `trending_banners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
