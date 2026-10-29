<?php
// flights-hotels.php - Flights & Hotels Search Interface
$page_title = "Flights & Luxury Hotels";
require_once __DIR__ . '/includes/header.php';

// Fetch Featured Hotels from database
$hotels_stmt = $pdo->query("SELECT * FROM hotels ORDER BY id ASC");
$hotels = $hotels_stmt->fetchAll();
?>

<!-- Header Banner -->
<section class="py-5 bg-dark-charcoal text-white text-center border-bottom border-gold">
    <div class="container py-4">
        <span class="section-subtitle">Luxury Concierge Reservations</span>
        <h1 class="font-heading display-4 fw-bold mb-3 text-white">First-Class Flights & 5-Star Hotels</h1>
        <p class="text-light opacity-75 mx-auto" style="max-width: 650px;">
            Reserve premiere airline seats and world-renowned luxury hotel stays with instant confirmation and VIP concierge support.
        </p>
    </div>
</section>

<!-- Search Tabs Section -->
<section class="py-5 bg-warm-ivory">
    <div class="container">
        <!-- Tab Navigation -->
        <ul class="nav nav-pills justify-content-center mb-4" id="bookingTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active btn-gold px-4 py-2 me-3 fs-5" id="flights-tab" data-bs-toggle="tab" data-bs-target="#flights" type="button" role="tab">
                    <i class="fa-solid fa-plane me-2"></i>Search Flights
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link btn-outline-gold px-4 py-2 fs-5" id="hotels-tab" data-bs-toggle="tab" data-bs-target="#hotels" type="button" role="tab">
                    <i class="fa-solid fa-hotel me-2"></i>Search Luxury Hotels
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="bookingTabContent">
            <!-- Flights Tab -->
            <div class="tab-pane fade show active" id="flights" role="tabpanel">
                <div class="card bg-cream-card border-gold p-4 shadow-sm mb-5">
                    <h3 class="font-heading text-main mb-3"><i class="fa-solid fa-plane-departure text-gold me-2"></i>Flight Search Engine</h3>
                    <form id="flightSearchForm">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label small fw-bold text-uppercase text-muted">Departure City</label>
                                <select id="flightOrigin" class="form-select form-control-luxury">
                                    <option value="New York (JFK)">New York (JFK)</option>
                                    <option value="London (LHR)">London (LHR)</option>
                                    <option value="Dubai (DXB)">Dubai (DXB)</option>
                                    <option value="Tokyo (HND)">Tokyo (Haneda)</option>
                                    <option value="Zurich (ZRH)">Zurich (ZRH)</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-bold text-uppercase text-muted">Destination City</label>
                                <select id="flightDest" class="form-select form-control-luxury">
                                    <option value="Tokyo (NRT)">Tokyo (NRT), Japan</option>
                                    <option value="Istanbul (IST)">Istanbul (IST), Turkey</option>
                                    <option value="Dubai (DXB)">Dubai (DXB), UAE</option>
                                    <option value="Zurich (ZRH)">Zurich (ZRH), Switzerland</option>
                                    <option value="Beijing (PEK)">Beijing (PEK), China</option>
                                    <option value="Islamabad (ISB)">Islamabad (ISB), Pakistan</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-bold text-uppercase text-muted">Departure Date</label>
                                <input type="date" id="flightDate" class="form-control form-control-luxury" value="2026-11-10" min="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-bold text-uppercase text-muted">Cabin Class</label>
                                <select class="form-select form-control-luxury">
                                    <option value="First">First Class Suite</option>
                                    <option value="Business" selected>Business Class</option>
                                    <option value="Premium Economy">Premium Economy</option>
                                </select>
                            </div>
                            <div class="col-12 text-end mt-4">
                                <button type="submit" class="btn btn-gold btn-lg px-5">
                                    <i class="fa-solid fa-magnifying-glass me-2"></i>Find Luxury Flights
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Flight Search Results Container -->
                <div id="flightResultsContainer">
                    <div class="alert alert-light border border-gold text-center py-4">
                        <i class="fa-solid fa-plane-up text-gold fs-1 mb-2"></i>
                        <h4 class="font-heading mb-1">Search Available Routes Above</h4>
                        <p class="text-muted small mb-0">Select your departure city, destination, and travel dates to generate real-time flight schedules.</p>
                    </div>
                </div>
            </div>

            <!-- Hotels Tab -->
            <div class="tab-pane fade" id="hotels" role="tabpanel">
                <div class="text-center mb-5">
                    <span class="section-subtitle">5-Star Accommodations</span>
                    <h2 class="section-title">Luxury Partner Hotels & Resorts</h2>
                </div>

                <div class="row g-4">
                    <?php foreach ($hotels as $htl): ?>
                        <div class="col-lg-4 col-md-6">
                            <div class="travel-card">
                                <div class="travel-card-img-wrap" style="height: 220px;">
                                    <img src="<?php echo htmlspecialchars($htl['image']); ?>" alt="<?php echo htmlspecialchars($htl['name']); ?>" loading="lazy">
                                    <span class="card-badge-gold"><i class="fa-solid fa-star me-1 text-gold"></i><?php echo $htl['star_rating']; ?>-Star Luxury</span>
                                </div>
                                <div class="travel-card-body">
                                    <div class="text-muted small mb-1"><i class="fa-solid fa-location-dot text-gold me-1"></i><?php echo htmlspecialchars($htl['city']); ?>, <?php echo htmlspecialchars($htl['country']); ?></div>
                                    <h3 class="travel-card-title fs-5"><?php echo htmlspecialchars($htl['name']); ?></h3>
                                    <p class="text-muted small line-clamp-2 mb-3"><?php echo htmlspecialchars($htl['description']); ?></p>
                                    
                                    <div class="bg-light p-2 rounded mb-3 small">
                                        <strong class="text-main"><i class="fa-solid fa-concierge-bell text-gold me-1"></i>Amenities:</strong>
                                        <div class="text-muted text-truncate"><?php echo htmlspecialchars($htl['amenities']); ?></div>
                                    </div>

                                    <div class="mt-auto d-flex justify-content-between align-items-center pt-3 border-top border-gold">
                                        <div>
                                            <span class="travel-card-price fs-5"><?php echo format_currency($htl['price_per_night']); ?></span>
                                            <span class="text-muted small">/ night</span>
                                        </div>
                                        <button class="btn btn-gold btn-sm px-3" onclick="bookDemoItem('hotel', '<?php echo addslashes($htl['name']); ?> (<?php echo addslashes($htl['city']); ?>)', <?php echo $htl['price_per_night']; ?>)">
                                            Reserve Suite
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
