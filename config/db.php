<?php
// config/db.php - Wanderlust Travels Database Connection & Auto-Setup

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('DB_NAME', 'wanderlust_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_HOST', '127.0.0.1');

$pdo = null;

try {
    // Attempt MySQL Connection
    $dsn = "mysql:host=" . DB_HOST . ";charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_TIMEOUT => 2
    ];
    
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `" . DB_NAME . "`");
    $db_type = 'mysql';
} catch (Exception $e) {
    // Fallback to SQLite if MySQL is unavailable
    try {
        $sqlite_dir = __DIR__ . '/../database';
        if (!file_exists($sqlite_dir)) {
            mkdir($sqlite_dir, 0777, true);
        }
        $sqlite_path = $sqlite_dir . '/wanderlust.sqlite';
        $pdo = new PDO("sqlite:" . $sqlite_path);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $db_type = 'sqlite';
    } catch (Exception $ex) {
        die("Database connection failed: " . $ex->getMessage());
    }
}

// Auto-create schema and seed tables if empty
function initialize_database($pdo, $db_type) {
    $auto_inc = ($db_type === 'sqlite') ? "AUTOINCREMENT" : "AUTO_INCREMENT";
    
    $queries = [
        "CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY $auto_inc,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(150) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            phone VARCHAR(50) DEFAULT NULL,
            passport_no VARCHAR(50) DEFAULT NULL,
            role VARCHAR(20) DEFAULT 'user',
            avatar VARCHAR(255) DEFAULT 'default_avatar.jpg',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )",
        "CREATE TABLE IF NOT EXISTS destinations (
            id INTEGER PRIMARY KEY $auto_inc,
            name VARCHAR(100) NOT NULL,
            country VARCHAR(100) NOT NULL,
            region VARCHAR(100) NOT NULL,
            image VARCHAR(500) NOT NULL,
            tagline VARCHAR(255) NOT NULL,
            description TEXT NOT NULL,
            attractions TEXT NOT NULL,
            best_time VARCHAR(100) NOT NULL,
            currency VARCHAR(50) NOT NULL,
            language VARCHAR(50) NOT NULL,
            visa_required VARCHAR(50) NOT NULL,
            featured TINYINT DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )",
        "CREATE TABLE IF NOT EXISTS packages (
            id INTEGER PRIMARY KEY $auto_inc,
            destination_id INTEGER DEFAULT NULL,
            title VARCHAR(200) NOT NULL,
            slug VARCHAR(200) NOT NULL,
            country VARCHAR(100) NOT NULL,
            duration VARCHAR(50) NOT NULL,
            price DECIMAL(10,2) NOT NULL,
            original_price DECIMAL(10,2) DEFAULT NULL,
            image VARCHAR(500) NOT NULL,
            travel_type VARCHAR(50) NOT NULL,
            rating DECIMAL(2,1) DEFAULT 4.9,
            reviews_count INT DEFAULT 24,
            highlights TEXT NOT NULL,
            itinerary TEXT NOT NULL,
            inclusions TEXT NOT NULL,
            exclusions TEXT NOT NULL,
            hotel_included VARCHAR(200) NOT NULL,
            featured TINYINT DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )",
        "CREATE TABLE IF NOT EXISTS hotels (
            id INTEGER PRIMARY KEY $auto_inc,
            name VARCHAR(200) NOT NULL,
            country VARCHAR(100) NOT NULL,
            city VARCHAR(100) NOT NULL,
            star_rating INT DEFAULT 5,
            price_per_night DECIMAL(10,2) NOT NULL,
            image VARCHAR(500) NOT NULL,
            description TEXT NOT NULL,
            amenities TEXT NOT NULL,
            location_address VARCHAR(255) NOT NULL,
            featured TINYINT DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )",
        "CREATE TABLE IF NOT EXISTS experiences (
            id INTEGER PRIMARY KEY $auto_inc,
            title VARCHAR(200) NOT NULL,
            category VARCHAR(50) NOT NULL,
            country VARCHAR(100) NOT NULL,
            city VARCHAR(100) NOT NULL,
            duration VARCHAR(50) NOT NULL,
            price DECIMAL(10,2) NOT NULL,
            image VARCHAR(500) NOT NULL,
            description TEXT NOT NULL,
            highlights TEXT NOT NULL,
            included TEXT NOT NULL,
            featured TINYINT DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )",
        "CREATE TABLE IF NOT EXISTS blog_posts (
            id INTEGER PRIMARY KEY $auto_inc,
            title VARCHAR(200) NOT NULL,
            slug VARCHAR(200) NOT NULL,
            category VARCHAR(100) NOT NULL,
            author VARCHAR(100) DEFAULT 'Wanderlust Editorial',
            image VARCHAR(500) NOT NULL,
            summary TEXT NOT NULL,
            content TEXT NOT NULL,
            read_time VARCHAR(20) DEFAULT '5 min read',
            published_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )",
        "CREATE TABLE IF NOT EXISTS bookings (
            id INTEGER PRIMARY KEY $auto_inc,
            user_id INTEGER NOT NULL,
            item_type VARCHAR(50) NOT NULL, -- 'package', 'flight', 'hotel', 'experience'
            item_id INTEGER DEFAULT NULL,
            item_title VARCHAR(200) NOT NULL,
            travel_date DATE NOT NULL,
            guests INT DEFAULT 1,
            total_price DECIMAL(10,2) NOT NULL,
            status VARCHAR(30) DEFAULT 'Pending', -- 'Pending', 'Confirmed', 'Completed', 'Cancelled'
            notes TEXT DEFAULT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )",
        "CREATE TABLE IF NOT EXISTS visa_requests (
            id INTEGER PRIMARY KEY $auto_inc,
            user_id INTEGER DEFAULT NULL,
            full_name VARCHAR(150) NOT NULL,
            email VARCHAR(150) NOT NULL,
            phone VARCHAR(50) NOT NULL,
            destination_country VARCHAR(100) NOT NULL,
            visa_type VARCHAR(50) NOT NULL,
            travel_date DATE NOT NULL,
            passport_no VARCHAR(50) DEFAULT NULL,
            status VARCHAR(30) DEFAULT 'In Review', -- 'In Review', 'Processing', 'Approved', 'Rejected'
            notes TEXT DEFAULT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )",
        "CREATE TABLE IF NOT EXISTS wishlist (
            id INTEGER PRIMARY KEY $auto_inc,
            user_id INTEGER NOT NULL,
            item_type VARCHAR(50) NOT NULL, -- 'package', 'destination', 'experience'
            item_id INTEGER NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )",
        "CREATE TABLE IF NOT EXISTS custom_trips (
            id INTEGER PRIMARY KEY $auto_inc,
            user_id INTEGER NOT NULL,
            trip_title VARCHAR(200) NOT NULL,
            destination VARCHAR(100) NOT NULL,
            start_date DATE NOT NULL,
            end_date DATE NOT NULL,
            hotel_tier VARCHAR(50) NOT NULL,
            guests INT DEFAULT 1,
            estimated_budget DECIMAL(10,2) NOT NULL,
            itinerary_json TEXT NOT NULL,
            status VARCHAR(30) DEFAULT 'Draft',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )",
        "CREATE TABLE IF NOT EXISTS contact_messages (
            id INTEGER PRIMARY KEY $auto_inc,
            name VARCHAR(150) NOT NULL,
            email VARCHAR(150) NOT NULL,
            subject VARCHAR(200) NOT NULL,
            message TEXT NOT NULL,
            status VARCHAR(20) DEFAULT 'Unread',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )"
    ];

    foreach ($queries as $q) {
        $pdo->exec($q);
    }

    // Seed default users if empty
    $check_user = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    if ($check_user == 0) {
        $admin_pass = password_hash("admin123", PASSWORD_BCRYPT);
        $user_pass = password_hash("user123", PASSWORD_BCRYPT);
        
        $pdo->exec("INSERT INTO users (name, email, password, phone, passport_no, role) VALUES 
            ('Administrator', 'admin@wanderlust.com', '$admin_pass', '+1 (800) 555-0199', 'US987654321', 'admin'),
            ('Alexander Vance', 'user@wanderlust.com', '$user_pass', '+1 (555) 234-5678', 'US123456789', 'user')");
    }

    // Seed destinations if empty
    $check_dest = $pdo->query("SELECT COUNT(*) FROM destinations")->fetchColumn();
    if ($check_dest == 0) {
        $destinations = [
            [
                'name' => 'Kyoto & Tokyo',
                'country' => 'Japan',
                'region' => 'Asia',
                'image' => 'https://images.unsplash.com/photo-1493976040374-85c8e12f0c0e?q=80&w=1200&auto=format&fit=crop',
                'tagline' => 'Timeless Shrines, Cherry Blossoms & Modern Elegance',
                'description' => 'Experience the harmonious blend of ancient traditions and hyper-modern sophistication. Discover tranquil Zen gardens, historic geisha districts in Gion, high-speed Shinkansen bullet trains, and world-class Michelin dining.',
                'attractions' => 'Fushimi Inari Shrine, Arashiyama Bamboo Grove, Kinkaku-ji (Golden Pavilion), Mount Fuji, Tokyo Skytree',
                'best_time' => 'March – May (Sakura) & Oct – Nov (Autumn Foliage)',
                'currency' => 'Japanese Yen (JPY)',
                'language' => 'Japanese',
                'visa_required' => 'E-Visa / Visa Free (68 countries)',
                'featured' => 1
            ],
            [
                'name' => 'Cappadocia & Istanbul',
                'country' => 'Turkey',
                'region' => 'Middle East',
                'image' => 'https://images.unsplash.com/photo-1641128324972-af3212f0f6bd?q=80&w=1200&auto=format&fit=crop',
                'tagline' => 'Where East Meets West in Golden Splendor',
                'description' => 'Soar in hot air balloons over magical fairy chimneys, explore underground cities, and wander through Ottoman palaces and Byzantine bazaars in Istanbul.',
                'attractions' => 'Goreme Open Air Museum, Hagia Sophia, Blue Mosque, Bosphorus Cruise, Grand Bazaar',
                'best_time' => 'April – June & September – November',
                'currency' => 'Turkish Lira (TRY)',
                'language' => 'Turkish',
                'visa_required' => 'E-Visa Available Online',
                'featured' => 1
            ],
            [
                'name' => 'Dubai & Desert Oasis',
                'country' => 'United Arab Emirates',
                'region' => 'Middle East',
                'image' => 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?q=80&w=1200&auto=format&fit=crop',
                'tagline' => 'Futuristic Horizons & Unrivaled Arabian Luxury',
                'description' => 'Immerse yourself in world-renowned ultra-luxury architecture, private desert safaris under starry nights, high-end shopping, and Michelin-starred dining experiences.',
                'attractions' => 'Burj Khalifa, Museum of the Future, Palm Jumeirah, Desert Dune Safari, Dubai Marina Cruise',
                'best_time' => 'November – April',
                'currency' => 'UAE Dirham (AED)',
                'language' => 'Arabic & English',
                'visa_required' => 'Visa on Arrival / E-Visa',
                'featured' => 1
            ],
            [
                'name' => 'Swiss Alps & Zurich',
                'country' => 'Switzerland',
                'region' => 'Europe',
                'image' => 'https://images.unsplash.com/photo-1530122037265-a5f1f91d3b99?q=80&w=1200&auto=format&fit=crop',
                'tagline' => 'Snow-Capped Peaks, Glacier Trains & Crystal Lakes',
                'description' => 'Surrender to breathtaking Alpine landscapes, pristine crystal lakes, iconic Matterhorn views, private luxury train journeys, and fine Swiss chocolatier workshops.',
                'attractions' => 'Matterhorn & Zermatt, Glacier Express, Lake Lucerne, Jungfraujoch, Interlaken Adventure',
                'best_time' => 'June – September (Hiking) & Dec – March (Skiing)',
                'currency' => 'Swiss Franc (CHF)',
                'language' => 'German, French, Italian',
                'visa_required' => 'Schengen Visa Required',
                'featured' => 1
            ],
            [
                'name' => 'Great Wall & Beijing',
                'country' => 'China',
                'region' => 'Asia',
                'image' => 'https://images.unsplash.com/photo-1508804185872-d7badad00f7d?q=80&w=1200&auto=format&fit=crop',
                'tagline' => 'Ancient Dynasties & Architectural Marvels',
                'description' => 'Walk upon the epic Great Wall of China, walk through imperial halls in the Forbidden City, explore Guilin’s serene karst mountain landscapes, and sample authentic Peking Duck.',
                'attractions' => 'Great Wall (Mutianyu), Forbidden City, Summer Palace, Guilin Karst Mountains, Shanghai Skyline',
                'best_time' => 'September – November & April – May',
                'currency' => 'Chinese Yuan (CNY)',
                'language' => 'Mandarin Chinese',
                'visa_required' => 'Tourist Visa / Transit Visa Free',
                'featured' => 1
            ],
            [
                'name' => 'Hunza Valley & Karakoram',
                'country' => 'Pakistan',
                'region' => 'South Asia',
                'image' => 'https://images.unsplash.com/photo-1586350977771-b3b0abd50c82?q=80&w=1200&auto=format&fit=crop',
                'tagline' => 'Heaven on Earth Amid Majestic 8,000m Peaks',
                'description' => 'Discover the legendary Hunza Valley along the ancient Silk Road. Gaze upon Rakaposhi, walk across high suspension bridges, explore 800-year-old Baltit Fort, and experience unmatched mountain hospitality.',
                'attractions' => 'Hunza Valley, Passu Cones, Attabad Lake, Khunjerab Pass (China Border), Fairy Meadows',
                'best_time' => 'April – October (Spring Blossom & Autumn Colors)',
                'currency' => 'Pakistani Rupee (PKR)',
                'language' => 'Urdu & English',
                'visa_required' => 'E-Visa / Visa on Arrival',
                'featured' => 1
            ]
        ];

        $stmt = $pdo->prepare("INSERT INTO destinations (name, country, region, image, tagline, description, attractions, best_time, currency, language, visa_required, featured) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($destinations as $d) {
            $stmt->execute([$d['name'], $d['country'], $d['region'], $d['image'], $d['tagline'], $d['description'], $d['attractions'], $d['best_time'], $d['currency'], $d['language'], $d['visa_required'], $d['featured']]);
        }
    }

    // Seed packages if empty
    $check_pkg = $pdo->query("SELECT COUNT(*) FROM packages")->fetchColumn();
    if ($check_pkg == 0) {
        $packages = [
            [
                'destination_id' => 1,
                'title' => 'Kyoto & Tokyo Blossom Odyssey',
                'slug' => 'kyoto-tokyo-blossom-odyssey',
                'country' => 'Japan',
                'duration' => '7 Days / 6 Nights',
                'price' => 3450.00,
                'original_price' => 3950.00,
                'image' => 'https://images.unsplash.com/photo-1493976040374-85c8e12f0c0e?q=80&w=1200&auto=format&fit=crop',
                'travel_type' => 'Luxury Culture',
                'rating' => 4.9,
                'reviews_count' => 48,
                'highlights' => 'Shinkansen Bullet Train Passes, Private Tea Ceremony in Gion, Mount Fuji Day Expedition with Hot Springs, Michelin Dining Reservations, 5-Star Hotel Accommodations.',
                'itinerary' => 'Day 1: Arrival in Tokyo & Sunset Dinner at Ginza | Day 2: Historic Asakusa & Private Tea Ceremony | Day 3: Bullet Train to Kyoto & Fushimi Inari Shrine | Day 4: Arashiyama Bamboo Forest & Gion Geisha District | Day 5: Day Trip to Mount Fuji & Onsen Retreat | Day 6: Modern Shinjuku & Farewell Dinner | Day 7: Departure from Narita/Haneda',
                'inclusions' => '5-Star Hotel Stay, Daily Gourmet Breakfast, JR Rail Pass First Class, English Private Guide, Entrance Tickets, Airport VIP Transfers',
                'exclusions' => 'International Airfare, Personal Souvenirs, Optional Helicopter Tour',
                'hotel_included' => 'The Ritz-Carlton Kyoto & Park Hyatt Tokyo',
                'featured' => 1
            ],
            [
                'destination_id' => 2,
                'title' => 'Grand Ottoman & Cappadocia Balloons',
                'slug' => 'grand-ottoman-cappadocia-balloons',
                'country' => 'Turkey',
                'duration' => '8 Days / 7 Nights',
                'price' => 2890.00,
                'original_price' => 3200.00,
                'image' => 'https://images.unsplash.com/photo-1641128324972-af3212f0f6bd?q=80&w=1200&auto=format&fit=crop',
                'travel_type' => 'Romantic & Historic',
                'rating' => 5.0,
                'reviews_count' => 62,
                'highlights' => 'VVIP Sunrise Hot Air Balloon Ride in Cappadocia, Private Yacht Cruise on Bosphorus, Luxury Cave Suite Experience, Hagia Sophia & Topkapi Palace Guided Tour.',
                'itinerary' => 'Day 1: Arrival in Istanbul & Bosphorus Yacht Dinner | Day 2: Old City Marvels (Hagia Sophia & Grand Bazaar) | Day 3: Flight to Cappadocia & Cave Hotel Check-in | Day 4: Sunrise Balloon Ride & Fairy Chimney Valley Trek | Day 5: Underground City & Turkish Cultural Night | Day 6: Return to Istanbul & Hammam Spa Treatment | Day 7: Spice Bazaar & Leisure Shopping | Day 8: Airport Transfer',
                'inclusions' => 'Luxury Cave Suite & 5-Star Istanbul Hotel, Domestic Flights, Private Hot Air Balloon Ticket, Private Guide & Transfers, All Breakfasts & 3 Special Dinners',
                'exclusions' => 'International Airfare, Visa Fees, Personal Shopping',
                'hotel_included' => 'Sultan Cave Suites Cappadocia & Ciragan Palace Kempinski Istanbul',
                'featured' => 1
            ],
            [
                'destination_id' => 3,
                'title' => 'Ultimate Luxury Dubai & Desert Retreat',
                'slug' => 'ultimate-luxury-dubai-desert-retreat',
                'country' => 'United Arab Emirates',
                'duration' => '5 Days / 4 Nights',
                'price' => 3100.00,
                'original_price' => 3600.00,
                'image' => 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?q=80&w=1200&auto=format&fit=crop',
                'travel_type' => 'Ultra Luxury',
                'rating' => 4.9,
                'reviews_count' => 35,
                'highlights' => 'Helicopter Tour over Palm Jumeirah, Private Royal Desert Safari with Vintage Land Rovers, Burj Khalifa At The Top VIP Lounge, Luxury Yacht Charter.',
                'itinerary' => 'Day 1: Chauffeur Arrival & Burj Al Arab Check-in | Day 2: Private City Tour & Helicopter Flight | Day 3: VIP Royal Desert Safari & Fine Dining Under Stars | Day 4: Private Yacht Cruise & Museum of the Future | Day 5: Chauffeur Airport Departure',
                'inclusions' => 'Burj Al Arab Stay, Chauffeur Rolls-Royce / Mercedes Service, Helicopter Flight, Desert Glamping Dinner, All Breakfasts & VIP Museum Passes',
                'exclusions' => 'International Flights, Personal Souvenirs',
                'hotel_included' => 'Burj Al Arab Jumeirah & Bab Al Shams Desert Resort',
                'featured' => 1
            ],
            [
                'destination_id' => 4,
                'title' => 'Alpine Wonders & Glacier Express',
                'slug' => 'alpine-wonders-glacier-express',
                'country' => 'Switzerland',
                'duration' => '6 Days / 5 Nights',
                'price' => 4200.00,
                'original_price' => 4800.00,
                'image' => 'https://images.unsplash.com/photo-1530122037265-a5f1f91d3b99?q=80&w=1200&auto=format&fit=crop',
                'travel_type' => 'Scenic & Nature',
                'rating' => 5.0,
                'reviews_count' => 29,
                'highlights' => 'First Class Excellence Class on Glacier Express Train, Zermatt Matterhorn Cable Car Ride, Lake Lucerne Steamboat Cruise, Swiss Fondue & Wine Tasting.',
                'itinerary' => 'Day 1: Arrival in Zurich & Private City Tour | Day 2: Scenic Train to Lucerne & Lake Steamboat Cruise | Day 3: Glacier Express First Class Journey to Zermatt | Day 4: Gornergrat Matterhorn Viewpoint & Alpine Trail | Day 5: Return to Zurich & Chocolatier Masterclass | Day 6: Departure',
                'inclusions' => 'First-Class Swiss Travel Pass, 5-Star Boutique Hotels, Glacier Express Excellence Seats, Matterhorn Passes, Daily Breakfast',
                'exclusions' => 'International Flights, Dinner Drinks',
                'hotel_included' => 'The Dolder Grand Zurich & Grand Hotel Zermatterhof',
                'featured' => 1
            ],
            [
                'destination_id' => 5,
                'title' => 'Imperial Dynasties & Great Wall Tour',
                'slug' => 'imperial-dynasties-great-wall-tour',
                'country' => 'China',
                'duration' => '8 Days / 7 Nights',
                'price' => 2950.00,
                'original_price' => 3400.00,
                'image' => 'https://images.unsplash.com/photo-1508804185872-d7badad00f7d?q=80&w=1200&auto=format&fit=crop',
                'travel_type' => 'Heritage & Culture',
                'rating' => 4.8,
                'reviews_count' => 41,
                'highlights' => 'Private Cable Car & Mutianyu Great Wall Sunset, Forbidden City VIP Access, High-Speed Bullet Train to Guilin, Li River Karst Cruise.',
                'itinerary' => 'Day 1: Arrival in Beijing & Welcome Peking Duck Feast | Day 2: Forbidden City & Temple of Heaven | Day 3: Private Great Wall Tour & Sunset Toast | Day 4: High-Speed Train to Guilin | Day 5: Li River Karst Cruise to Yangshuo | Day 6: Bamboo Rafting & Countryside Cycling | Day 7: Fly to Shanghai & Bund Skyline Cruise | Day 8: Departure',
                'inclusions' => '5-Star Luxury Accommodations, Internal High-Speed Rail & Flights, Private English Guide, All Entry Fees, Daily Gourmet Breakfasts',
                'exclusions' => 'International Airfare, China Visa Fees',
                'hotel_included' => 'Four Seasons Beijing & Banyan Tree Yangshuo',
                'featured' => 1
            ],
            [
                'destination_id' => 6,
                'title' => 'Majestic Hunza & Karakoram Highway Expedition',
                'slug' => 'majestic-hunza-karakoram-highway-expedition',
                'country' => 'Pakistan',
                'duration' => '9 Days / 8 Nights',
                'price' => 2450.00,
                'original_price' => 2900.00,
                'image' => 'https://images.unsplash.com/photo-1586350977771-b3b0abd50c82?q=80&w=1200&auto=format&fit=crop',
                'travel_type' => 'Adventure & Nature',
                'rating' => 5.0,
                'reviews_count' => 54,
                'highlights' => 'Explore Ancient Silk Road Fortress Baltit & Altit, Boat Ride on Sapphire Attabad Lake, Passu Cones Suspension Bridge, Khunjerab Pass (Highest Border on Earth), Serena Resort Stays.',
                'itinerary' => 'Day 1: Arrival in Islamabad & Heritage City Sightseeing | Day 2: Mountain Flight to Skardu or Scenic Karakoram Highway Drive | Day 3: Shangrila Lake & Shigar Fort Palace | Day 4: Drive to Hunza Valley & Sunset at Duikar Rakaposhi Viewpoint | Day 5: 800-Year-Old Baltit Fort & Karimabad Bazaar | Day 6: Attabad Lake Speedboat & Passu Cones Suspension Bridge | Day 7: Day Excursion to Khunjerab Pass (China Border) | Day 8: Return Flight to Islamabad & Farewell Dinner | Day 9: Departure',
                'inclusions' => 'Serena Luxury Resort Accommodations, 4x4 Prado Land Cruiser Transport, Domestic Flights, Private Guide & Security Liaison, All Meals & Cultural Performances',
                'exclusions' => 'International Flights, Personal Souvenirs',
                'hotel_included' => 'Hunza Serena Inn & Serena Shigar Fort Palace',
                'featured' => 1
            ]
        ];

        $stmt = $pdo->prepare("INSERT INTO packages (destination_id, title, slug, country, duration, price, original_price, image, travel_type, rating, reviews_count, highlights, itinerary, inclusions, exclusions, hotel_included, featured) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($packages as $p) {
            $stmt->execute([$p['destination_id'], $p['title'], $p['slug'], $p['country'], $p['duration'], $p['price'], $p['original_price'], $p['image'], $p['travel_type'], $p['rating'], $p['reviews_count'], $p['highlights'], $p['itinerary'], $p['inclusions'], $p['exclusions'], $p['hotel_included'], $p['featured']]);
        }
    }

    // Seed hotels if empty
    $check_hotel = $pdo->query("SELECT COUNT(*) FROM hotels")->fetchColumn();
    if ($check_hotel == 0) {
        $hotels = [
            [
                'name' => 'The Ritz-Carlton Kyoto',
                'country' => 'Japan',
                'city' => 'Kyoto',
                'star_rating' => 5,
                'price_per_night' => 850.00,
                'image' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=1200&auto=format&fit=crop',
                'description' => 'Overlooking the Kamogawa River with panoramic views of the Higashiyama mountains, combining traditional Japanese craftsmanship with modern ultra-luxury.',
                'amenities' => 'Private Onsen Spa, Michelin-starred Restaurant, Concierge, Swimming Pool, River View Suites, Free High-Speed WiFi',
                'location_address' => 'Kamogawa Nijo-Ohashi Hotori, Nakagyo-ku, Kyoto',
                'featured' => 1
            ],
            [
                'name' => 'Burj Al Arab Jumeirah',
                'country' => 'United Arab Emirates',
                'city' => 'Dubai',
                'star_rating' => 5,
                'price_per_night' => 1600.00,
                'image' => 'https://images.unsplash.com/photo-1582719508461-905c673771fd?q=80&w=1200&auto=format&fit=crop',
                'description' => 'The world-famous sail-shaped icon of Dubai offering suite-only luxury, private butler service, underwater dining, and Rolls-Royce airport transfers.',
                'amenities' => 'Private Beach, Helipad, 24-Hour Butler Service, 9 World-Class Restaurants, Infinity Terrace Pool',
                'location_address' => 'Jumeirah St - Umm Suqeim 3, Dubai',
                'featured' => 1
            ],
            [
                'name' => 'Sultan Cave Suites',
                'country' => 'Turkey',
                'city' => 'Cappadocia',
                'star_rating' => 5,
                'price_per_night' => 450.00,
                'image' => 'https://images.unsplash.com/photo-1590490360182-c33d57733427?q=80&w=1200&auto=format&fit=crop',
                'description' => 'Boutique cave hotel located atop Aydinli Hill in Goreme, offering famous panoramic rooftop terrace views of morning hot air balloon flights.',
                'amenities' => 'Rooftop Terrace View, Turkish Bath Hammam, Gourmet Anatolian Restaurant, Free Breakfast, Airport Shuttle',
                'location_address' => 'Aydinli Mahallesi, Cave Street No:40, Goreme, Cappadocia',
                'featured' => 1
            ],
            [
                'name' => 'The Dolder Grand',
                'country' => 'Switzerland',
                'city' => 'Zurich',
                'star_rating' => 5,
                'price_per_night' => 980.00,
                'image' => 'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?q=80&w=1200&auto=format&fit=crop',
                'description' => 'Perched on the Adlisberg hill above Zurich, featuring 4,000 sqm spa, 2 Michelin-starred restaurant, and commanding views of Zurich lake and Alps.',
                'amenities' => '4,000m² Luxury Spa, 2 Michelin Stars Restaurant, Art Collection, Shuttle to City Center, Indoor Pool',
                'location_address' => 'Kurhausstrasse 65, 8032 Zürich, Switzerland',
                'featured' => 1
            ],
            [
                'name' => 'Hunza Serena Inn & Heritage Lodge',
                'country' => 'Pakistan',
                'city' => 'Hunza Valley',
                'star_rating' => 5,
                'price_per_night' => 320.00,
                'image' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=1200&auto=format&fit=crop',
                'description' => 'Set in lush apricot orchards with dramatic views of Ultar Glacier and Rakaposhi peak, offering traditional Hunzakut hospitality and luxury amenities.',
                'amenities' => 'Mountain View Balconies, Traditional Osho Dinning, Organic Apricot Garden, Private 4x4 Excursions, Cultural Music Evenings',
                'location_address' => 'Zero Point, Karimabad, Hunza Valley, Gilgit-Baltistan',
                'featured' => 1
            ]
        ];

        $stmt = $pdo->prepare("INSERT INTO hotels (name, country, city, star_rating, price_per_night, image, description, amenities, location_address, featured) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($hotels as $h) {
            $stmt->execute([$h['name'], $h['country'], $h['city'], $h['star_rating'], $h['price_per_night'], $h['image'], $h['description'], $h['amenities'], $h['location_address'], $h['featured']]);
        }
    }

    // Seed experiences if empty
    $check_exp = $pdo->query("SELECT COUNT(*) FROM experiences")->fetchColumn();
    if ($check_exp == 0) {
        $experiences = [
            [
                'title' => 'Cappadocia Sunrise Hot Air Balloon Flight',
                'category' => 'Adventure',
                'country' => 'Turkey',
                'city' => 'Cappadocia',
                'duration' => '3 Hours',
                'price' => 260.00,
                'image' => 'https://images.unsplash.com/photo-1641128324972-af3212f0f6bd?q=80&w=1200&auto=format&fit=crop',
                'description' => 'Float gently over fairy chimneys, rock formations, and golden valleys at sunrise with champagne toast upon landing.',
                'highlights' => '1 Hour Flight Time, Commemorative Flight Certificate, Champagne Toast, Hotel Transfer Included',
                'included' => 'Pickup & Dropoff, Flight Ticket, Light Breakfast, Insurance',
                'featured' => 1
            ],
            [
                'title' => 'Traditional Kyoto Geisha Tea Ceremony & Kaiseki',
                'category' => 'Cultural',
                'country' => 'Japan',
                'city' => 'Kyoto',
                'duration' => '4 Hours',
                'price' => 380.00,
                'image' => 'https://images.unsplash.com/photo-1493976040374-85c8e12f0c0e?q=80&w=1200&auto=format&fit=crop',
                'description' => 'Private authentic tea ceremony hosted by an experienced Maiko/Geiko in an exclusive 150-year-old Kyoto Machiya teahouse.',
                'highlights' => 'Private Machiya Teahouse, Multi-Course Kaiseki Dinner, Traditional Koto Music Performance, Photo Session',
                'included' => 'Tea Master Services, Full Kaiseki Meal, Matcha & Wagashi Sweets, Interpreter',
                'featured' => 1
            ],
            [
                'title' => 'Dubai Private Yacht Charter & Sunset Cruise',
                'category' => 'Sightseeing',
                'country' => 'United Arab Emirates',
                'city' => 'Dubai',
                'duration' => '3 Hours',
                'price' => 550.00,
                'image' => 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?q=80&w=1200&auto=format&fit=crop',
                'description' => 'Sail along the Dubai Marina, Atlantis The Palm, and Burj Al Arab aboard a luxury 50ft private yacht with personal crew.',
                'highlights' => 'Private 50ft Yacht, Professional Captain & Crew, Gourmet Refreshments, Swimming Stop at Lagoon',
                'included' => 'Yacht Rental, Soft Drinks & Ice, Sound System, BBQ Grill Available',
                'featured' => 1
            ],
            [
                'title' => 'Alpine Helicopter Tour & Glacier Glacier Landing',
                'category' => 'Adventure',
                'country' => 'Switzerland',
                'city' => 'Zermatt',
                'duration' => '2 Hours',
                'price' => 720.00,
                'image' => 'https://images.unsplash.com/photo-1530122037265-a5f1f91d3b99?q=80&w=1200&auto=format&fit=crop',
                'description' => 'Fly close to the summit of the iconic Matterhorn and land on a pristine alpine glacier for photos and champagne.',
                'highlights' => '30-Minute Flight, Glacier Touchdown, Views of 4,000m Peaks, Champagne Toast',
                'included' => 'Helicopter Flight, Alpine Pilot, Glacier Landing Permit, Safety Gear',
                'featured' => 1
            ]
        ];

        $stmt = $pdo->prepare("INSERT INTO experiences (title, category, country, city, duration, price, image, description, highlights, included, featured) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($experiences as $e) {
            $stmt->execute([$e['title'], $e['category'], $e['country'], $e['city'], $e['duration'], $e['price'], $e['image'], $e['description'], $e['highlights'], $e['included'], $e['featured']]);
        }
    }

    // Seed blog posts if empty
    $check_blog = $pdo->query("SELECT COUNT(*) FROM blog_posts")->fetchColumn();
    if ($check_blog == 0) {
        $blog_posts = [
            [
                'title' => 'The Ultimate 2026 Japan Travel Guide: Cherry Blossoms, Culture & Hidden Gems',
                'slug' => 'ultimate-2026-japan-travel-guide',
                'category' => 'Destination Guides',
                'author' => 'Elena Rostova',
                'image' => 'https://images.unsplash.com/photo-1493976040374-85c8e12f0c0e?q=80&w=1200&auto=format&fit=crop',
                'summary' => 'Planning a trip to Japan? From booking the JR Pass to navigating Tokyo transit and enjoying private Kyoto teahouses, here is everything you need to know for an unforgettable journey.',
                'content' => 'Japan remains one of the world\'s most captivating travel destinations. Whether you are seeking the tranquility of Kyoto’s ancient temples or the dazzling lights of Shinjuku, careful preparation elevates your journey from good to extraordinary...',
                'read_time' => '6 min read'
            ],
            [
                'title' => 'Experiencing the Magic of Cappadocia: Hot Air Balloons, Cave Hotels & Ancient History',
                'slug' => 'magic-of-cappadocia-guide',
                'category' => 'Luxury Travel',
                'author' => 'Julian Sterling',
                'image' => 'https://images.unsplash.com/photo-1641128324972-af3212f0f6bd?q=80&w=1200&auto=format&fit=crop',
                'summary' => 'Discover why Cappadocia is the undisputed capital of fairy-tale travel. Learn how to choose the right cave hotel, secure the best balloon flight slots, and explore secret valleys.',
                'content' => 'Floating high above Goreme Valley as the sun paints the fairy chimneys in hues of amber and rose is an experience etched in memory forever...',
                'read_time' => '5 min read'
            ],
            [
                'title' => 'Schengen & Switzerland Visa Masterclass: Requirements, Documents & Success Tips',
                'slug' => 'schengen-switzerland-visa-masterclass',
                'category' => 'Visa Assistance',
                'author' => 'Wanderlust Visa Team',
                'image' => 'https://images.unsplash.com/photo-1530122037265-a5f1f91d3b99?q=80&w=1200&auto=format&fit=crop',
                'summary' => 'Navigating visa applications can feel daunting. Our senior visa consultants break down the exact checklist, financial proof requirements, and interview tips for 100% approval confidence.',
                'content' => 'Securing a Schengen visa requires precision, clear documentation, and a well-structured itinerary. In this detailed breakdown, we review the top 5 mistakes applicants make and how our Concierge Visa Team guarantees smooth processing...',
                'read_time' => '7 min read'
            ],
            [
                'title' => 'Unveiling Hunza Valley: The Crown Jewel of the Karakoram Highway',
                'slug' => 'unveiling-hunza-valley-karakoram',
                'category' => 'Destination Spotlights',
                'author' => 'Tariq Mansoor',
                'image' => 'https://images.unsplash.com/photo-1586350977771-b3b0abd50c82?q=80&w=1200&auto=format&fit=crop',
                'summary' => 'Nestled among 8,000-meter snow peaks, Hunza Valley offers ancient fortress history, sapphire lakes, and hospitality that warms the soul. Here is your definitive guide.',
                'content' => 'The Karakoram Highway is often dubbed the 8th Wonder of the World. Traversing through deep granite gorges and opening into the emerald terraced fields of Hunza Valley, it is an expedition into pure natural grandeur...',
                'read_time' => '8 min read'
            ]
        ];

        $stmt = $pdo->prepare("INSERT INTO blog_posts (title, slug, category, author, image, summary, content, read_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($blog_posts as $b) {
            $stmt->execute([$b['title'], $b['slug'], $b['category'], $b['author'], $b['image'], $b['summary'], $b['content'], $b['read_time']]);
        }
    }

    // Seed sample bookings for demo user if empty
    $check_bk = $pdo->query("SELECT COUNT(*) FROM bookings")->fetchColumn();
    if ($check_bk == 0) {
        $pdo->exec("INSERT INTO bookings (user_id, item_type, item_id, item_title, travel_date, guests, total_price, status, notes) VALUES 
            (2, 'package', 1, 'Kyoto & Tokyo Blossom Odyssey', '2026-11-15', 2, 6900.00, 'Confirmed', 'Requested high-floor room with river view.'),
            (2, 'experience', 1, 'Cappadocia Sunrise Hot Air Balloon Flight', '2026-12-10', 2, 520.00, 'Pending', 'Honeymoon anniversary trip.')");
    }

    // Seed sample visa requests if empty
    $check_vr = $pdo->query("SELECT COUNT(*) FROM visa_requests")->fetchColumn();
    if ($check_vr == 0) {
        $pdo->exec("INSERT INTO visa_requests (user_id, full_name, email, phone, destination_country, visa_type, travel_date, passport_no, status, notes) VALUES 
            (2, 'Alexander Vance', 'user@wanderlust.com', '+1 (555) 234-5678', 'Switzerland', 'Schengen Tourist Visa', '2026-12-01', 'US123456789', 'Processing', 'All documents submitted to Zurich consulate liaison.')");
    }

    // Seed sample wishlist if empty
    $check_wl = $pdo->query("SELECT COUNT(*) FROM wishlist")->fetchColumn();
    if ($check_wl == 0) {
        $pdo->exec("INSERT INTO wishlist (user_id, item_type, item_id) VALUES (2, 'package', 2), (2, 'destination', 4)");
    }
}

// Execute DB setup
initialize_database($pdo, $db_type);
