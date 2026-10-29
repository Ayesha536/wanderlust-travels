<?php
// index.php - Home Page
$page_title = "Home - International Luxury Travel Agency";
require_once __DIR__ . '/includes/header.php';

// Fetch Featured Destinations
$dest_stmt = $pdo->query("SELECT * FROM destinations WHERE featured = 1 ORDER BY id ASC LIMIT 6");
$destinations = $dest_stmt->fetchAll();

// Fetch Featured Packages
$pkg_stmt = $pdo->query("SELECT * FROM packages WHERE featured = 1 ORDER BY id ASC LIMIT 6");
$packages = $pkg_stmt->fetchAll();

// Fetch Featured Experiences
$exp_stmt = $pdo->query("SELECT * FROM experiences WHERE featured = 1 ORDER BY id ASC LIMIT 4");
$experiences = $exp_stmt->fetchAll();

$user_id = $logged_user['id'] ?? null;
$wishlist_ids = get_wishlist_item_ids($pdo, $user_id, 'package');
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container position-relative z-1 py-5">
        <div class="row align-items-center">
            <div class="col-lg-8 text-center text-lg-start">
                <span class="hero-tagline"><i class="fa-solid fa-compass me-2"></i>Explore • Experience • Belong</span>
                <h1 class="hero-title">Discover the world.<br>We’ll handle the journey.</h1>
                <p class="hero-subtitle">
                    Immerse yourself in extraordinary global destinations with bespoke itineraries, five-star luxury accommodations, private guides, and 24/7 VIP concierge service.
                </p>
                
                <!-- Quick Destination Search Widget -->
                <div class="hero-search-box mt-4">
                    <form action="packages.php" method="GET" class="row g-3 align-items-end">
                        <div class="col-md-4 text-start">
                            <label class="form-label font-sans small text-uppercase text-muted fw-bold mb-1">
                                <i class="fa-solid fa-location-dot me-1 text-gold"></i>Destination
                            </label>
                            <select name="dest" class="form-select form-control-luxury">
                                <option value="">Where do you want to go?</option>
                                <option value="Japan">Japan (Kyoto & Tokyo)</option>
                                <option value="Turkey">Turkey (Cappadocia & Istanbul)</option>
                                <option value="United Arab Emirates">UAE (Dubai & Desert Oasis)</option>
                                <option value="Switzerland">Switzerland (Swiss Alps)</option>
                                <option value="China">China (Great Wall & Beijing)</option>
                                <option value="Pakistan">Pakistan (Hunza Valley)</option>
                            </select>
                        </div>
                        <div class="col-md-3 text-start">
                            <label class="form-label font-sans small text-uppercase text-muted fw-bold mb-1">
                                <i class="fa-solid fa-calendar me-1 text-gold"></i>Travel Month
                            </label>
                            <input type="month" name="month" class="form-control form-control-luxury" value="2026-11">
                        </div>
                        <div class="col-md-3 text-start">
                            <label class="form-label font-sans small text-uppercase text-muted fw-bold mb-1">
                                <i class="fa-solid fa-filter me-1 text-gold"></i>Travel Style
                            </label>
                            <select name="type" class="form-select form-control-luxury">
                                <option value="">All Styles</option>
                                <option value="Luxury Culture">Luxury Culture</option>
                                <option value="Romantic & Historic">Romantic & Historic</option>
                                <option value="Ultra Luxury">Ultra Luxury</option>
                                <option value="Adventure & Nature">Adventure & Nature</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-gold w-100 py-2 fs-6">
                                <i class="fa-solid fa-magnifying-glass me-1"></i>Search
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Destinations Section -->
<section class="py-5 bg-warm-ivory">
    <div class="container py-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-4">
            <div>
                <span class="section-subtitle">Curated Global Horizons</span>
                <h2 class="section-title">Featured Destinations</h2>
            </div>
            <a href="destinations.php" class="btn btn-outline-gold mt-2 mt-md-0">View All Destinations <i class="fa-solid fa-arrow-right ms-1"></i></a>
        </div>

        <div class="row g-4">
            <?php foreach ($destinations as $dest): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="travel-card">
                        <div class="travel-card-img-wrap">
                            <img src="<?php echo htmlspecialchars($dest['image']); ?>" alt="<?php echo htmlspecialchars($dest['name']); ?>" loading="lazy">
                            <span class="card-badge-gold"><?php echo htmlspecialchars($dest['country']); ?></span>
                        </div>
                        <div class="travel-card-body">
                            <div class="text-muted small mb-1"><i class="fa-solid fa-globe me-1 text-gold"></i><?php echo htmlspecialchars($dest['region']); ?></div>
                            <h3 class="travel-card-title"><?php echo htmlspecialchars($dest['name']); ?></h3>
                            <p class="text-muted small line-clamp-2 mb-3">
                                <?php echo htmlspecialchars($dest['tagline']); ?>
                            </p>
                            <div class="mt-auto d-flex justify-content-between align-items-center pt-3 border-top border-gold">
                                <span class="small text-muted"><i class="fa-solid fa-sun me-1 text-gold"></i><?php echo htmlspecialchars($dest['best_time']); ?></span>
                                <a href="destination-detail.php?id=<?php echo $dest['id']; ?>" class="btn btn-gold btn-sm px-3">Explore</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Popular Tour Packages Section -->
<section class="py-5 bg-cream-card border-top border-bottom border-gold">
    <div class="container py-4">
        <div class="text-center mb-5">
            <span class="section-subtitle">Unforgettable Expeditions</span>
            <h2 class="section-title">Popular Tour Packages</h2>
            <p class="text-muted mx-auto" style="max-width: 600px;">
                Handcrafted luxury journeys including 5-star accommodations, private transportation, domestic transfers, and immersive guided tours.
            </p>
        </div>

        <div class="row g-4">
            <?php foreach ($packages as $pkg): ?>
                <?php $is_wished = in_array($pkg['id'], $wishlist_ids); ?>
                <div class="col-lg-4 col-md-6">
                    <div class="travel-card">
                        <div class="travel-card-img-wrap">
                            <img src="<?php echo htmlspecialchars($pkg['image']); ?>" alt="<?php echo htmlspecialchars($pkg['title']); ?>" loading="lazy">
                            <span class="card-badge-gold"><?php echo htmlspecialchars($pkg['duration']); ?></span>
                            <button class="card-wishlist-btn <?php echo $is_wished ? 'active' : ''; ?>" data-id="<?php echo $pkg['id']; ?>" data-type="package" title="Add to Wishlist">
                                <i class="<?php echo $is_wished ? 'fa-solid' : 'fa-regular'; ?> fa-heart"></i>
                            </button>
                        </div>
                        <div class="travel-card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-light text-dark border"><i class="fa-solid fa-compass text-gold me-1"></i><?php echo htmlspecialchars($pkg['travel_type']); ?></span>
                                <span class="text-gold fw-bold small"><i class="fa-solid fa-star me-1"></i><?php echo $pkg['rating']; ?> (<?php echo $pkg['reviews_count']; ?>)</span>
                            </div>
                            <h3 class="travel-card-title"><?php echo htmlspecialchars($pkg['title']); ?></h3>
                            <p class="text-muted small mb-3">
                                <i class="fa-solid fa-hotel me-1 text-gold"></i><?php echo htmlspecialchars($pkg['hotel_included']); ?>
                            </p>
                            <div class="mt-auto d-flex justify-content-between align-items-center pt-3 border-top border-gold">
                                <div>
                                    <span class="text-muted small text-decoration-line-through me-1"><?php echo format_currency($pkg['original_price']); ?></span>
                                    <span class="travel-card-price"><?php echo format_currency($pkg['price']); ?></span>
                                </div>
                                <a href="package-detail.php?id=<?php echo $pkg['id']; ?>" class="btn btn-gold btn-sm px-3">View Details</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-5">
            <a href="packages.php" class="btn btn-charcoal btn-lg px-5">Explore All Tour Packages</a>
        </div>
    </div>
</section>

<!-- Our Services Section -->
<section class="py-5 bg-warm-ivory">
    <div class="container py-4">
        <div class="text-center mb-5">
            <span class="section-subtitle">Tailored Excellence</span>
            <h2 class="section-title">Our Premium Services</h2>
            <p class="text-muted mx-auto" style="max-width: 600px;">
                From individual custom trips to end-to-end corporate and leisure logistics, we provide comprehensive travel solutions.
            </p>
        </div>

        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="icon-box-luxury">
                    <div class="icon-box-icon"><i class="fa-solid fa-map-location-dot"></i></div>
                    <h4 class="font-heading mb-2">Curated Tour Packages</h4>
                    <p class="text-muted small mb-0">Complete luxury itineraries featuring hand-picked stays, private transfers, and English-speaking local guides.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="icon-box-luxury">
                    <div class="icon-box-icon"><i class="fa-solid fa-plane-departure"></i></div>
                    <h4 class="font-heading mb-2">First & Business Class Flights</h4>
                    <p class="text-muted small mb-0">Exclusive airline partnerships with Emirates, Qatar Airways, ANA, and Swiss Air with seat selection & lounge access.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="icon-box-luxury">
                    <div class="icon-box-icon"><i class="fa-solid fa-hotel"></i></div>
                    <h4 class="font-heading mb-2">Luxury Hotel Reservations</h4>
                    <p class="text-muted small mb-0">Access to 5-star luxury hotels, heritage palaces, private villas, and boutique cave suites with VIP upgrades.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="icon-box-luxury">
                    <div class="icon-box-icon"><i class="fa-solid fa-passport"></i></div>
                    <h4 class="font-heading mb-2">Concierge Visa Assistance</h4>
                    <p class="text-muted small mb-0">Fast-track visa consultation, document verification, and embassy liaison for Schengen, Japan, UAE, China, and more.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="icon-box-luxury">
                    <div class="icon-box-icon"><i class="fa-solid fa-camera-retro"></i></div>
                    <h4 class="font-heading mb-2">Bespoke Travel Experiences</h4>
                    <p class="text-muted small mb-0">Private hot air balloon flights, traditional tea ceremonies, private yacht cruises, and helicopter alpine tours.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="icon-box-luxury">
                    <div class="icon-box-icon"><i class="fa-solid fa-car"></i></div>
                    <h4 class="font-heading mb-2">VIP Airport Transfers</h4>
                    <p class="text-muted small mb-0">Chauffeur-driven luxury Mercedes-Benz, Rolls-Royce, and 4x4 Land Cruisers with fast-track terminal assistance.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose Us Section -->
<section class="py-5 bg-dark-charcoal text-white position-relative">
    <div class="container py-4">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="section-subtitle">The Wanderlust Distinction</span>
                <h2 class="section-title text-white">Why Choose Wanderlust Travels?</h2>
                <p class="text-light opacity-75 mb-4">
                    We believe travel is an art. For over a decade, we have established unparalleled relationships with the world's finest hotels, airlines, and local culture keepers.
                </p>

                <div class="d-flex gap-3 mb-4">
                    <div class="text-gold fs-3"><i class="fa-solid fa-award"></i></div>
                    <div>
                        <h5 class="font-heading text-white mb-1">100% Verified Quality Standards</h5>
                        <p class="text-muted small mb-0">Every hotel, guide, and experience is personally inspected by our quality assurance team.</p>
                    </div>
                </div>

                <div class="d-flex gap-3 mb-4">
                    <div class="text-gold fs-3"><i class="fa-solid fa-headset"></i></div>
                    <div>
                        <h5 class="font-heading text-white mb-1">24/7 Personal VIP Concierge</h5>
                        <p class="text-muted small mb-0">Dedicated travel advisor assigned to your journey from takeoff to arrival back home.</p>
                    </div>
                </div>

                <div class="d-flex gap-3">
                    <div class="text-gold fs-3"><i class="fa-solid fa-sliders"></i></div>
                    <div>
                        <h5 class="font-heading text-white mb-1">Tailor-Made Customization</h5>
                        <p class="text-muted small mb-0">Use our interactive Trip Planner to build your day-by-day itinerary with total flexibility.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="position-relative">
                    <img src="https://images.unsplash.com/photo-1540555700478-4be289fbecef?q=80&w=1000&auto=format&fit=crop" class="img-fluid rounded-3 border border-gold shadow-lg" alt="Luxury Travel">
                    <div class="position-absolute bottom-0 start-0 m-4 bg-warm-ivory text-main p-3 rounded-2 shadow-lg border border-gold d-none d-sm-block">
                        <div class="d-flex align-items-center gap-3">
                            <i class="fa-solid fa-circle-check text-gold fs-2"></i>
                            <div>
                                <div class="fw-bold font-heading fs-5">15,000+ Happy Travelers</div>
                                <div class="small text-muted">Worldwide 5-Star Rating</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Experiences Section -->
<section class="py-5 bg-warm-ivory">
    <div class="container py-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-4">
            <div>
                <span class="section-subtitle">Exclusive Activities</span>
                <h2 class="section-title">Signature Travel Experiences</h2>
            </div>
            <a href="experiences.php" class="btn btn-outline-gold mt-2 mt-md-0">View All Experiences <i class="fa-solid fa-arrow-right ms-1"></i></a>
        </div>

        <div class="row g-4">
            <?php foreach ($experiences as $exp): ?>
                <div class="col-lg-3 col-md-6">
                    <div class="travel-card">
                        <div class="travel-card-img-wrap" style="height: 200px;">
                            <img src="<?php echo htmlspecialchars($exp['image']); ?>" alt="<?php echo htmlspecialchars($exp['title']); ?>" loading="lazy">
                            <span class="card-badge-gold"><?php echo htmlspecialchars($exp['category']); ?></span>
                        </div>
                        <div class="travel-card-body">
                            <div class="text-muted small mb-1"><i class="fa-solid fa-location-dot text-gold me-1"></i><?php echo htmlspecialchars($exp['city']); ?>, <?php echo htmlspecialchars($exp['country']); ?></div>
                            <h4 class="font-heading fs-5 mb-2"><?php echo htmlspecialchars($exp['title']); ?></h4>
                            <div class="mt-auto d-flex justify-content-between align-items-center pt-2 border-top border-gold">
                                <span class="travel-card-price fs-5"><?php echo format_currency($exp['price']); ?></span>
                                <a href="experience-detail.php?id=<?php echo $exp['id']; ?>" class="btn btn-gold btn-sm">Book</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="py-5 bg-cream-card border-top border-gold">
    <div class="container py-4">
        <div class="text-center mb-5">
            <span class="section-subtitle">Voices of Distinction</span>
            <h2 class="section-title">What Our Travelers Say</h2>
        </div>

        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 p-4 border-gold shadow-sm">
                    <div class="text-gold mb-3 fs-5">
                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    </div>
                    <p class="text-muted fst-italic mb-4">
                        "Wanderlust Travels curated our Japan anniversary trip flawlessly. From private tea ceremonies in Kyoto to 5-star Ritz-Carlton stays, everything was perfection."
                    </p>
                    <div class="d-flex align-items-center mt-auto">
                        <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=150&auto=format&fit=crop" class="rounded-circle me-3" width="50" height="50" alt="Avatar">
                        <div>
                            <h6 class="mb-0 font-heading fw-bold">Victoria & James Kensington</h6>
                            <small class="text-muted">New York, USA</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="card h-100 p-4 border-gold shadow-sm">
                    <div class="text-gold mb-3 fs-5">
                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    </div>
                    <p class="text-muted fst-italic mb-4">
                        "The Cappadocia balloon flight and luxury cave suite experience in Turkey surpassed all expectations. The 24/7 concierge was always on hand!"
                    </p>
                    <div class="d-flex align-items-center mt-auto">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=150&auto=format&fit=crop" class="rounded-circle me-3" width="50" height="50" alt="Avatar">
                        <div>
                            <h6 class="mb-0 font-heading fw-bold">Dr. Aris Thorne</h6>
                            <small class="text-muted">London, UK</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="card h-100 p-4 border-gold shadow-sm">
                    <div class="text-gold mb-3 fs-5">
                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    </div>
                    <p class="text-muted fst-italic mb-4">
                        "The Hunza Valley expedition was a journey of a lifetime. The Karakoram Highway, Serena resort stays, and local hospitality were world-class."
                    </p>
                    <div class="d-flex align-items-center mt-auto">
                        <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?q=80&w=150&auto=format&fit=crop" class="rounded-circle me-3" width="50" height="50" alt="Avatar">
                        <div>
                            <h6 class="mb-0 font-heading fw-bold">Harrison Meyer</h6>
                            <small class="text-muted">Zurich, Switzerland</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action Banner -->
<section class="py-5 bg-gold text-white text-center">
    <div class="container py-3">
        <span class="text-uppercase font-sans small tracking-wide fw-bold">Your Journey Begins Today</span>
        <h2 class="font-heading display-5 my-2 fw-bold text-white">Ready to Plan Your Next Journey?</h2>
        <p class="lead mb-4 mx-auto opacity-90" style="max-width: 650px;">
            Let our senior travel specialists build a personalized travel itinerary tailored to your exact dates and preferences.
        </p>
        <div class="d-flex justify-content-center gap-3">
            <a href="trip-planner.php" class="btn btn-charcoal btn-lg px-4">Use Interactive Trip Planner</a>
            <a href="contact.php" class="btn btn-outline-light btn-lg px-4">Request Private Consultation</a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
