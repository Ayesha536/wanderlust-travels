-- Wanderlust Travels - Full Database Schema & Seed Data
-- Database: wanderlust_db

CREATE DATABASE IF NOT EXISTS `wanderlust_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `wanderlust_db`;

-- Table structure for `users`
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `passport_no` varchar(50) DEFAULT NULL,
  `role` varchar(20) DEFAULT 'user',
  `avatar` varchar(255) DEFAULT 'default_avatar.jpg',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed Users (Passwords: admin123 and user123)
INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `passport_no`, `role`) VALUES
(1, 'Administrator', 'admin@wanderlust.com', '$2y$10$w3Yg60dJz6qJ6d4/3l9LVOc4oM.U3o2lR.x3Gg.2Qe4Y8T1bZ6k7S', '+1 (800) 555-0199', 'US987654321', 'admin'),
(2, 'Alexander Vance', 'user@wanderlust.com', '$2y$10$Q7w5W9T8Y5u1K3z2M4N5O.1P2Q3R4S5T6U7V8W9X0Y1Z2A3B4C5D6', '+1 (555) 234-5678', 'US123456789', 'user');

-- Table structure for `destinations`
DROP TABLE IF EXISTS `destinations`;
CREATE TABLE `destinations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `country` varchar(100) NOT NULL,
  `region` varchar(100) NOT NULL,
  `image` varchar(500) NOT NULL,
  `tagline` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `attractions` text NOT NULL,
  `best_time` varchar(100) NOT NULL,
  `currency` varchar(50) NOT NULL,
  `language` varchar(50) NOT NULL,
  `visa_required` varchar(50) NOT NULL,
  `featured` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `destinations` (`id`, `name`, `country`, `region`, `image`, `tagline`, `description`, `attractions`, `best_time`, `currency`, `language`, `visa_required`, `featured`) VALUES
(1, 'Kyoto & Tokyo', 'Japan', 'Asia', 'https://images.unsplash.com/photo-1493976040374-85c8e12f0c0e?q=80&w=1200&auto=format&fit=crop', 'Timeless Shrines, Cherry Blossoms & Modern Elegance', 'Experience the harmonious blend of ancient traditions and hyper-modern sophistication.', 'Fushimi Inari Shrine, Arashiyama Bamboo Grove, Kinkaku-ji, Mount Fuji, Tokyo Skytree', 'March – May & Oct – Nov', 'Japanese Yen (JPY)', 'Japanese', 'E-Visa / Visa Free', 1),
(2, 'Cappadocia & Istanbul', 'Turkey', 'Middle East', 'https://images.unsplash.com/photo-1641128324972-af3212f0f6bd?q=80&w=1200&auto=format&fit=crop', 'Where East Meets West in Golden Splendor', 'Soar in hot air balloons over magical fairy chimneys, explore underground cities, and wander Ottoman palaces.', 'Goreme Open Air Museum, Hagia Sophia, Blue Mosque, Bosphorus Cruise', 'April – June & Sept – Nov', 'Turkish Lira (TRY)', 'Turkish', 'E-Visa Online', 1),
(3, 'Dubai & Desert Oasis', 'United Arab Emirates', 'Middle East', 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?q=80&w=1200&auto=format&fit=crop', 'Futuristic Horizons & Unrivaled Arabian Luxury', 'Immerse yourself in world-renowned ultra-luxury architecture, private desert safaris, and Michelin dining.', 'Burj Khalifa, Museum of the Future, Palm Jumeirah, Desert Dune Safari', 'November – April', 'UAE Dirham (AED)', 'Arabic & English', 'Visa on Arrival / E-Visa', 1),
(4, 'Swiss Alps & Zurich', 'Switzerland', 'Europe', 'https://images.unsplash.com/photo-1530122037265-a5f1f91d3b99?q=80&w=1200&auto=format&fit=crop', 'Snow-Capped Peaks, Glacier Trains & Crystal Lakes', 'Breathtaking Alpine landscapes, pristine crystal lakes, iconic Matterhorn views, and fine Swiss chocolate.', 'Matterhorn & Zermatt, Glacier Express, Lake Lucerne, Jungfraujoch', 'June – Sept & Dec – March', 'Swiss Franc (CHF)', 'German, French, Italian', 'Schengen Visa Required', 1),
(5, 'Great Wall & Beijing', 'China', 'Asia', 'https://images.unsplash.com/photo-1508804185872-d7badad00f7d?q=80&w=1200&auto=format&fit=crop', 'Ancient Dynasties & Architectural Marvels', 'Walk upon the epic Great Wall of China, walk through imperial halls in the Forbidden City, and visit Guilin.', 'Great Wall (Mutianyu), Forbidden City, Summer Palace, Guilin Karst Mountains', 'September – November & April – May', 'Chinese Yuan (CNY)', 'Mandarin Chinese', 'Tourist Visa Required', 1),
(6, 'Hunza Valley & Karakoram', 'Pakistan', 'South Asia', 'https://images.unsplash.com/photo-1586350977771-b3b0abd50c82?q=80&w=1200&auto=format&fit=crop', 'Heaven on Earth Amid Majestic 8,000m Peaks', 'Discover legendary Hunza Valley along the Silk Road. Gaze upon Rakaposhi and walk high suspension bridges.', 'Hunza Valley, Passu Cones, Attabad Lake, Khunjerab Pass, Fairy Meadows', 'April – October', 'Pakistani Rupee (PKR)', 'Urdu & English', 'E-Visa / Visa on Arrival', 1);

-- Table structure for `packages`
DROP TABLE IF EXISTS `packages`;
CREATE TABLE `packages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `destination_id` int(11) DEFAULT NULL,
  `title` varchar(200) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `country` varchar(100) NOT NULL,
  `duration` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `original_price` decimal(10,2) DEFAULT NULL,
  `image` varchar(500) NOT NULL,
  `travel_type` varchar(50) NOT NULL,
  `rating` decimal(2,1) DEFAULT 4.9,
  `reviews_count` int(11) DEFAULT 24,
  `highlights` text NOT NULL,
  `itinerary` text NOT NULL,
  `inclusions` text NOT NULL,
  `exclusions` text NOT NULL,
  `hotel_included` varchar(200) NOT NULL,
  `featured` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `packages` (`id`, `destination_id`, `title`, `slug`, `country`, `duration`, `price`, `original_price`, `image`, `travel_type`, `rating`, `reviews_count`, `highlights`, `itinerary`, `inclusions`, `exclusions`, `hotel_included`, `featured`) VALUES
(1, 1, 'Kyoto & Tokyo Blossom Odyssey', 'kyoto-tokyo-blossom-odyssey', 'Japan', '7 Days / 6 Nights', 3450.00, 3950.00, 'https://images.unsplash.com/photo-1493976040374-85c8e12f0c0e?q=80&w=1200&auto=format&fit=crop', 'Luxury Culture', 4.9, 48, 'Shinkansen Bullet Train Passes, Private Tea Ceremony, Mount Fuji Day Expedition, Michelin Dining Reservations', 'Day 1: Arrival & Ginza Dinner | Day 2: Asakusa & Tea Ceremony | Day 3: Bullet Train to Kyoto | Day 4: Bamboo Forest & Gion | Day 5: Mount Fuji Onsen | Day 6: Shinjuku | Day 7: Departure', '5-Star Hotels, Daily Breakfast, JR Pass First Class, English Private Guide', 'International Flights, Personal Souvenirs', 'The Ritz-Carlton Kyoto & Park Hyatt Tokyo', 1),
(2, 2, 'Grand Ottoman & Cappadocia Balloons', 'grand-ottoman-cappadocia-balloons', 'Turkey', '8 Days / 7 Nights', 2890.00, 3200.00, 'https://images.unsplash.com/photo-1641128324972-af3212f0f6bd?q=80&w=1200&auto=format&fit=crop', 'Romantic & Historic', 5.0, 62, 'Sunrise Hot Air Balloon Ride, Private Yacht Cruise on Bosphorus, Cave Suite Stay', 'Day 1: Arrival & Bosphorus Yacht Dinner | Day 2: Old City Marvels | Day 3: Flight to Cappadocia | Day 4: Sunrise Balloon Ride | Day 5: Underground City | Day 6: Return to Istanbul & Hammam | Day 7: Shopping | Day 8: Departure', 'Cave Suite & 5-Star Hotel, Domestic Flights, Hot Air Balloon Ticket, Private Guide', 'International Airfare, Personal Shopping', 'Sultan Cave Suites & Ciragan Palace Kempinski', 1);

-- Table structure for `hotels`
DROP TABLE IF EXISTS `hotels`;
CREATE TABLE `hotels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `country` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `star_rating` int(11) DEFAULT 5,
  `price_per_night` decimal(10,2) NOT NULL,
  `image` varchar(500) NOT NULL,
  `description` text NOT NULL,
  `amenities` text NOT NULL,
  `location_address` varchar(255) NOT NULL,
  `featured` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for `experiences`
DROP TABLE IF EXISTS `experiences`;
CREATE TABLE `experiences` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `category` varchar(50) NOT NULL,
  `country` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `duration` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(500) NOT NULL,
  `description` text NOT NULL,
  `highlights` text NOT NULL,
  `included` text NOT NULL,
  `featured` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for `blog_posts`
DROP TABLE IF EXISTS `blog_posts`;
CREATE TABLE `blog_posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `category` varchar(100) NOT NULL,
  `author` varchar(100) DEFAULT 'Wanderlust Editorial',
  `image` varchar(500) NOT NULL,
  `summary` text NOT NULL,
  `content` text NOT NULL,
  `read_time` varchar(20) DEFAULT '5 min read',
  `published_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for `bookings`
DROP TABLE IF EXISTS `bookings`;
CREATE TABLE `bookings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `item_type` varchar(50) NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `item_title` varchar(200) NOT NULL,
  `travel_date` date NOT NULL,
  `guests` int(11) DEFAULT 1,
  `total_price` decimal(10,2) NOT NULL,
  `status` varchar(30) DEFAULT 'Pending',
  `notes` text DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for `visa_requests`
DROP TABLE IF EXISTS `visa_requests`;
CREATE TABLE `visa_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `full_name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `destination_country` varchar(100) NOT NULL,
  `visa_type` varchar(50) NOT NULL,
  `travel_date` date NOT NULL,
  `passport_no` varchar(50) DEFAULT NULL,
  `status` varchar(30) DEFAULT 'In Review',
  `notes` text DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for `wishlist`
DROP TABLE IF EXISTS `wishlist`;
CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `item_type` varchar(50) NOT NULL,
  `item_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for `custom_trips`
DROP TABLE IF EXISTS `custom_trips`;
CREATE TABLE `custom_trips` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `trip_title` varchar(200) NOT NULL,
  `destination` varchar(100) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `hotel_tier` varchar(50) NOT NULL,
  `guests` int(11) DEFAULT 1,
  `estimated_budget` decimal(10,2) NOT NULL,
  `itinerary_json` text NOT NULL,
  `status` varchar(30) DEFAULT 'Draft',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for `contact_messages`
DROP TABLE IF EXISTS `contact_messages`;
CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `status` varchar(20) DEFAULT 'Unread',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
