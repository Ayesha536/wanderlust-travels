<?php
// contact.php - Contact & Office Locations Page
$page_title = "Contact Us & Global Offices";
require_once __DIR__ . '/includes/header.php';

$preselect_subject = isset($_GET['subject']) ? sanitize($_GET['subject']) : '';
?>

<!-- Header Banner -->
<section class="py-5 bg-dark-charcoal text-white text-center border-bottom border-gold">
    <div class="container py-4">
        <span class="section-subtitle">Connect With Us</span>
        <h1 class="font-heading display-4 fw-bold mb-3 text-white">Contact Wanderlust Travels</h1>
        <p class="text-light opacity-75 mx-auto" style="max-width: 650px;">
            Our senior travel advisors and concierge team are available 24/7 to assist with bespoke itineraries, group bookings, and visa inquiries.
        </p>
    </div>
</section>

<!-- Global Office Cards -->
<section class="py-5 bg-warm-ivory">
    <div class="container">
        <div class="row g-4 mb-5">
            <!-- Dubai HQ -->
            <div class="col-lg-3 col-md-6">
                <div class="card bg-cream-card border-gold p-4 h-100 shadow-sm text-center">
                    <div class="text-gold fs-1 mb-2"><i class="fa-solid fa-building-user"></i></div>
                    <h4 class="font-heading">Dubai Global HQ</h4>
                    <p class="text-muted small mb-2">Level 42, Almas Tower, Jumeirah Lakes Towers, Dubai, UAE</p>
                    <div class="small fw-bold text-main mb-1"><i class="fa-solid fa-phone text-gold me-1"></i>+971 4 555 0199</div>
                    <div class="small text-muted"><i class="fa-solid fa-envelope text-gold me-1"></i>dubai@wanderlust.com</div>
                </div>
            </div>

            <!-- Zurich Office -->
            <div class="col-lg-3 col-md-6">
                <div class="card bg-cream-card border-gold p-4 h-100 shadow-sm text-center">
                    <div class="text-gold fs-1 mb-2"><i class="fa-solid fa-mountain-city"></i></div>
                    <h4 class="font-heading">Zurich Office</h4>
                    <p class="text-muted small mb-2">Bahnhofstrasse 10, 8001 Zürich, Switzerland</p>
                    <div class="small fw-bold text-main mb-1"><i class="fa-solid fa-phone text-gold me-1"></i>+41 44 211 0088</div>
                    <div class="small text-muted"><i class="fa-solid fa-envelope text-gold me-1"></i>zurich@wanderlust.com</div>
                </div>
            </div>

            <!-- Tokyo Branch -->
            <div class="col-lg-3 col-md-6">
                <div class="card bg-cream-card border-gold p-4 h-100 shadow-sm text-center">
                    <div class="text-gold fs-1 mb-2"><i class="fa-solid fa-torii-gate"></i></div>
                    <h4 class="font-heading">Tokyo Branch</h4>
                    <p class="text-muted small mb-2">Roppongi Hills Mori Tower 28F, Minato-ku, Tokyo, Japan</p>
                    <div class="small fw-bold text-main mb-1"><i class="fa-solid fa-phone text-gold me-1"></i>+81 3 5555 0123</div>
                    <div class="small text-muted"><i class="fa-solid fa-envelope text-gold me-1"></i>tokyo@wanderlust.com</div>
                </div>
            </div>

            <!-- Islamabad Branch -->
            <div class="col-lg-3 col-md-6">
                <div class="card bg-cream-card border-gold p-4 h-100 shadow-sm text-center">
                    <div class="text-gold fs-1 mb-2"><i class="fa-solid fa-kaaba"></i></div>
                    <h4 class="font-heading">Islamabad Branch</h4>
                    <p class="text-muted small mb-2">Serena Business Complex, Khayaban-e-Suhrawardy, Islamabad</p>
                    <div class="small fw-bold text-main mb-1"><i class="fa-solid fa-phone text-gold me-1"></i>+92 51 260 0100</div>
                    <div class="small text-muted"><i class="fa-solid fa-envelope text-gold me-1"></i>islamabad@wanderlust.com</div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Contact Form -->
            <div class="col-lg-7">
                <div class="card bg-cream-card border-gold p-4 p-md-5 shadow-lg">
                    <h3 class="font-heading text-main mb-2"><i class="fa-solid fa-paper-plane text-gold me-2"></i>Send Us a Message</h3>
                    <p class="text-muted small mb-4">Complete the form below and a dedicated travel specialist will reply within 2 hours.</p>

                    <form id="contactForm">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-uppercase text-muted">Your Full Name *</label>
                                <input type="text" name="name" class="form-control form-control-luxury" required placeholder="Alexander Vance" value="<?php echo htmlspecialchars($logged_user['name'] ?? ''); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-uppercase text-muted">Your Email Address *</label>
                                <input type="email" name="email" class="form-control form-control-luxury" required placeholder="alexander@example.com" value="<?php echo htmlspecialchars($logged_user['email'] ?? ''); ?>">
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold text-uppercase text-muted">Subject / Topic *</label>
                                <input type="text" name="subject" class="form-control form-control-luxury" required placeholder="General Travel Inquiry" value="<?php echo htmlspecialchars($preselect_subject); ?>">
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold text-uppercase text-muted">Your Message *</label>
                                <textarea name="message" class="form-control form-control-luxury" rows="5" required placeholder="Tell us about your desired travel dates, destination preferences, or specific questions..."></textarea>
                            </div>
                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-gold btn-lg w-100 py-3 fw-bold">
                                    <i class="fa-solid fa-paper-plane me-2"></i>Submit Message
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- FAQ Section -->
            <div class="col-lg-5">
                <div class="card bg-dark-charcoal text-white border-gold p-4 shadow-sm">
                    <h4 class="font-heading text-gold mb-3"><i class="fa-solid fa-circle-question me-2"></i>Frequently Asked Questions</h4>
                    
                    <div class="accordion accordion-flush" id="faqAccordion">
                        <div class="accordion-item bg-transparent border-bottom border-secondary">
                            <h2 class="accordion-header">
                                <button class="accordion-button font-heading fs-6 bg-transparent text-white" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                    How far in advance should I book?
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-light opacity-75 small">
                                    For peak seasons (Japan Cherry Blossoms in April, Swiss Skiing in December, or Cappadocia Hot Air Ballooning), we recommend booking 60 to 90 days in advance.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item bg-transparent border-bottom border-secondary">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed font-heading fs-6 bg-transparent text-white" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                    Do you handle visa applications?
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-light opacity-75 small">
                                    Yes! Our Concierge Visa Team assists with document audit, flight/hotel booking vouchers, and priority appointment booking for Schengen, Japan, Turkey, UAE, and China.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item bg-transparent border-bottom border-secondary">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed font-heading fs-6 bg-transparent text-white" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                    Can I customize existing tour packages?
                                </button>
                            </h2>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-light opacity-75 small">
                                    Absolutely. Every tour package can be modified to add extra nights, upgrade hotel suites, or insert private helicopter/yacht excursions using our Trip Planner.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item bg-transparent">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed font-heading fs-6 bg-transparent text-white" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                    What is your cancellation policy?
                                </button>
                            </h2>
                            <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-light opacity-75 small">
                                    Most luxury packages include 100% refundable cancellation up to 14 days prior to departure.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.getElementById('contactForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    fetch('api/submit_contact.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            showToast('Message Sent!', data.message, 'success');
            this.reset();
        } else {
            showToast('Error', data.message || 'Could not send message.', 'danger');
        }
    });
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
