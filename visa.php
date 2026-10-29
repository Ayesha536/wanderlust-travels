<?php
// visa.php - Visa Assistance Directory & Consultation Form
$page_title = "Global Visa Assistance";
require_once __DIR__ . '/includes/header.php';

$preselect_country = isset($_GET['country']) ? sanitize($_GET['country']) : 'Switzerland';
?>

<!-- Header Banner -->
<section class="py-5 bg-dark-charcoal text-white text-center border-bottom border-gold">
    <div class="container py-4">
        <span class="section-subtitle">Seamless Global Mobility</span>
        <h1 class="font-heading display-4 fw-bold mb-3 text-white">Concierge Visa Assistance</h1>
        <p class="text-light opacity-75 mx-auto" style="max-width: 650px;">
            Fast-track document audit, priority embassy appointments, e-visa filing, and expert visa advisory for over 50 global destinations.
        </p>
    </div>
</section>

<!-- Main Visa Section -->
<section class="py-5 bg-warm-ivory">
    <div class="container">
        <!-- 4 Step Process Cards -->
        <div class="row g-4 mb-5 text-center">
            <div class="col-lg-3 col-md-6">
                <div class="card bg-cream-card border-gold p-4 h-100 shadow-sm">
                    <div class="fs-1 text-gold mb-2 font-heading fw-bold">01</div>
                    <h5 class="font-heading">Consultation & Profile Audit</h5>
                    <p class="text-muted small mb-0">Our senior visa officer reviews your travel dates, passport validity, and financial proofs.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card bg-cream-card border-gold p-4 h-100 shadow-sm">
                    <div class="fs-1 text-gold mb-2 font-heading fw-bold">02</div>
                    <h5 class="font-heading">Document Checklist & Cover Letter</h5>
                    <p class="text-muted small mb-0">We compile verified flight itineraries, hotel reservations, and custom embassy cover letters.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card bg-cream-card border-gold p-4 h-100 shadow-sm">
                    <div class="fs-1 text-gold mb-2 font-heading fw-bold">03</div>
                    <h5 class="font-heading">Embassy Submission / E-Visa Filing</h5>
                    <p class="text-muted small mb-0">We schedule priority biometric slots and submit formal e-visa or paper visa applications.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card bg-cream-card border-gold p-4 h-100 shadow-sm">
                    <div class="fs-1 text-gold mb-2 font-heading fw-bold">04</div>
                    <h5 class="font-heading">Passport Delivery & Travel Prep</h5>
                    <p class="text-muted small mb-0">Receive your stamped passport or digital e-visa along with pre-departure briefing.</p>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Document Checklist & Countries Offered -->
            <div class="col-lg-6">
                <div class="card bg-cream-card border-gold p-4 mb-4 shadow-sm">
                    <h3 class="font-heading text-main mb-3 border-bottom border-gold pb-2"><i class="fa-solid fa-earth-americas text-gold me-2"></i>Supported Visa Destinations</h3>
                    
                    <div class="accordion accordion-flush" id="visaCountriesAccordion">
                        <div class="accordion-item bg-transparent border-bottom">
                            <h2 class="accordion-header">
                                <button class="accordion-button font-heading fs-5 bg-transparent" type="button" data-bs-toggle="collapse" data-bs-target="#countrySchengen">
                                    <i class="fa-solid fa-passport me-2 text-gold"></i>Switzerland & Schengen Area
                                </button>
                            </h2>
                            <div id="countrySchengen" class="accordion-collapse collapse show" data-bs-parent="#visaCountriesAccordion">
                                <div class="accordion-body text-muted small">
                                    <strong>Visa Type:</strong> Schengen Short-Stay Tourist / Business Visa (Type C)<br>
                                    <strong>Processing Time:</strong> 10 – 15 Working Days<br>
                                    <strong>Key Requirements:</strong> Passport valid 6+ months, 3-Month Bank Statements, Flight & Hotel Vouchers, Travel Insurance (€30,000 coverage).
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item bg-transparent border-bottom">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed font-heading fs-5 bg-transparent" type="button" data-bs-toggle="collapse" data-bs-target="#countryJapan">
                                    <i class="fa-solid fa-passport me-2 text-gold"></i>Japan Tourist & E-Visa
                                </button>
                            </h2>
                            <div id="countryJapan" class="accordion-collapse collapse" data-bs-parent="#visaCountriesAccordion">
                                <div class="accordion-body text-muted small">
                                    <strong>Visa Type:</strong> Japan E-Visa / Sticker Tourist Visa<br>
                                    <strong>Processing Time:</strong> 5 – 7 Working Days<br>
                                    <strong>Key Requirements:</strong> Passport copy, Tax Returns/Bank Proof, Daily Tour Itinerary Schedule, Guarantee Letter if sponsored.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item bg-transparent border-bottom">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed font-heading fs-5 bg-transparent" type="button" data-bs-toggle="collapse" data-bs-target="#countryTurkey">
                                    <i class="fa-solid fa-passport me-2 text-gold"></i>Turkey E-Visa & Sticker Visa
                                </button>
                            </h2>
                            <div id="countryTurkey" class="accordion-collapse collapse" data-bs-parent="#visaCountriesAccordion">
                                <div class="accordion-body text-muted small">
                                    <strong>Visa Type:</strong> Single / Multiple Entry E-Visa & Embassy Visa<br>
                                    <strong>Processing Time:</strong> 24 Hours (E-Visa) / 7 Days (Consulate)<br>
                                    <strong>Key Requirements:</strong> Valid passport, return ticket, hotel reservation confirmation.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item bg-transparent border-bottom">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed font-heading fs-5 bg-transparent" type="button" data-bs-toggle="collapse" data-bs-target="#countryDubai">
                                    <i class="fa-solid fa-passport me-2 text-gold"></i>United Arab Emirates (Dubai) E-Visa
                                </button>
                            </h2>
                            <div id="countryDubai" class="accordion-collapse collapse" data-bs-parent="#visaCountriesAccordion">
                                <div class="accordion-body text-muted small">
                                    <strong>Visa Type:</strong> 30-Day & 60-Day Tourist E-Visa<br>
                                    <strong>Processing Time:</strong> 48 – 72 Hours<br>
                                    <strong>Key Requirements:</strong> Passport clear scan, passport photo with white background.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item bg-transparent">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed font-heading fs-5 bg-transparent" type="button" data-bs-toggle="collapse" data-bs-target="#countryChina">
                                    <i class="fa-solid fa-passport me-2 text-gold"></i>China L-Tourist Visa & Business Visa
                                </button>
                            </h2>
                            <div id="countryChina" class="accordion-collapse collapse" data-bs-parent="#visaCountriesAccordion">
                                <div class="accordion-body text-muted small">
                                    <strong>Visa Type:</strong> Single / Double / Multiple Entry L-Visa<br>
                                    <strong>Processing Time:</strong> 4 – 7 Working Days<br>
                                    <strong>Key Requirements:</strong> Passport original, official invitation letter or hotel & flight itinerary, application form.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Document Checklist Box -->
                <div class="card bg-dark-charcoal text-white border-gold p-4 shadow-sm">
                    <h4 class="font-heading text-gold mb-3"><i class="fa-solid fa-list-check me-2"></i>Universal Document Checklist</h4>
                    <ul class="list-unstyled text-light opacity-90 small lh-lg mb-0">
                        <li><i class="fa-solid fa-check text-gold me-2"></i>Original Passport valid for at least 6 months with 2 blank pages</li>
                        <li><i class="fa-solid fa-check text-gold me-2"></i>2 Biometric Passport Photos (White Background, 35x45mm)</li>
                        <li><i class="fa-solid fa-check text-gold me-2"></i>3 to 6 Months Official Stamped Bank Statement</li>
                        <li><i class="fa-solid fa-check text-gold me-2"></i>Confirmed Return Flight Reservation & Hotel Booking Vouchers</li>
                        <li><i class="fa-solid fa-check text-gold me-2"></i>Employment Letter / Business Registration Certificate</li>
                        <li><i class="fa-solid fa-check text-gold me-2"></i>Custom Travel Itinerary & Purpose Statement Cover Letter</li>
                    </ul>
                </div>
            </div>

            <!-- Visa Consultation Request Form -->
            <div class="col-lg-6">
                <div class="card bg-cream-card border-gold p-4 shadow-lg">
                    <h3 class="font-heading text-main mb-1"><i class="fa-solid fa-file-signature text-gold me-2"></i>Request Visa Consultation</h3>
                    <p class="text-muted small mb-4">Submit your passport details and destination preference for a free document audit.</p>

                    <form id="visaForm">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-uppercase text-muted">Full Name *</label>
                                <input type="text" name="full_name" class="form-control form-control-luxury" required placeholder="Alexander Vance" value="<?php echo htmlspecialchars($logged_user['name'] ?? ''); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-uppercase text-muted">Email Address *</label>
                                <input type="email" name="email" class="form-control form-control-luxury" required placeholder="alexander@example.com" value="<?php echo htmlspecialchars($logged_user['email'] ?? ''); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-uppercase text-muted">Phone Number *</label>
                                <input type="tel" name="phone" class="form-control form-control-luxury" required placeholder="+1 (555) 000-0000">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-uppercase text-muted">Destination Country *</label>
                                <select name="destination_country" class="form-select form-control-luxury" required>
                                    <option value="Switzerland" <?php echo ($preselect_country === 'Switzerland') ? 'selected' : ''; ?>>Switzerland / Schengen Area</option>
                                    <option value="Japan" <?php echo ($preselect_country === 'Japan') ? 'selected' : ''; ?>>Japan</option>
                                    <option value="Turkey" <?php echo ($preselect_country === 'Turkey') ? 'selected' : ''; ?>>Turkey</option>
                                    <option value="United Arab Emirates" <?php echo ($preselect_country === 'United Arab Emirates' || $preselect_country === 'UAE') ? 'selected' : ''; ?>>United Arab Emirates (Dubai)</option>
                                    <option value="China" <?php echo ($preselect_country === 'China') ? 'selected' : ''; ?>>China</option>
                                    <option value="Pakistan" <?php echo ($preselect_country === 'Pakistan') ? 'selected' : ''; ?>>Pakistan</option>
                                    <option value="United States">United States (B1/B2)</option>
                                    <option value="United Kingdom">United Kingdom Standard Visitor</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-uppercase text-muted">Visa Category</label>
                                <select name="visa_type" class="form-select form-control-luxury">
                                    <option value="Tourist Visa" selected>Tourist Visa</option>
                                    <option value="Business Visa">Business Visa</option>
                                    <option value="Family Visit Visa">Family / Friend Visit</option>
                                    <option value="E-Visa Fast Track">E-Visa Fast Track</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-uppercase text-muted">Expected Travel Date</label>
                                <input type="date" name="travel_date" class="form-control form-control-luxury" value="2026-11-20" min="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold text-uppercase text-muted">Passport Number (Optional)</label>
                                <input type="text" name="passport_no" class="form-control form-control-luxury" placeholder="US123456789">
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold text-uppercase text-muted">Special Notes / Previous Visa History</label>
                                <textarea name="notes" class="form-control form-control-luxury" rows="3" placeholder="Mention previous visas held, employment status, or urgent travel dates..."></textarea>
                            </div>
                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-gold btn-lg w-100 py-3 fw-bold">
                                    <i class="fa-solid fa-paper-plane me-2"></i>Submit Visa Consultation Request
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.getElementById('visaForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    fetch('api/submit_visa.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            showToast('Request Submitted!', data.message, 'success');
            this.reset();
        } else {
            showToast('Error', data.message || 'Submission failed.', 'danger');
        }
    });
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
