<?php
// about.php - About Us Page
$page_title = "About Us - Luxury Travel Agency";
require_once __DIR__ . '/includes/header.php';
?>

<!-- Header Banner -->
<section class="py-5 bg-dark-charcoal text-white text-center border-bottom border-gold">
    <div class="container py-4">
        <span class="section-subtitle">Our Heritage</span>
        <h1 class="font-heading display-4 fw-bold mb-3 text-white">About Wanderlust Travels</h1>
        <p class="text-light opacity-75 mx-auto" style="max-width: 650px;">
            Redefining international luxury travel through bespoke itineraries, five-star hospitality, and immersive cultural authenticity.
        </p>
    </div>
</section>

<!-- Our Story Section -->
<section class="py-5 bg-warm-ivory">
    <div class="container py-4">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="section-subtitle">The Founders' Vision</span>
                <h2 class="section-title">Crafting Unforgettable Journeys Since 2012</h2>
                <p class="text-muted leading-relaxed mb-4">
                    Founded in Dubai with operational branches in Zurich, Tokyo, and Islamabad, Wanderlust Travels began with a simple philosophy: **“Explore • Experience • Belong”**. We believe true luxury travel is not merely about staying in 5-star hotels—it is about opening doors to private, transformative experiences.
                </p>
                <p class="text-muted leading-relaxed mb-4">
                    Whether arranging sunrise hot air balloon flights over Cappadocia, private tea ceremonies in Kyoto, alpine glacier train journeys through Switzerland, or expeditions along the Karakoram Highway in Pakistan, our concierge team delivers perfection down to every detail.
                </p>
                <div class="row g-3 text-center border-top border-gold pt-3">
                    <div class="col-4">
                        <h3 class="font-heading text-gold fw-bold display-6">14+</h3>
                        <small class="text-muted text-uppercase">Years Experience</small>
                    </div>
                    <div class="col-4">
                        <h3 class="font-heading text-gold fw-bold display-6">50+</h3>
                        <small class="text-muted text-uppercase">Countries Offered</small>
                    </div>
                    <div class="col-4">
                        <h3 class="font-heading text-gold fw-bold display-6">15k+</h3>
                        <small class="text-muted text-uppercase">Satisfied Guests</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=1000&auto=format&fit=crop" class="img-fluid rounded-3 border border-gold shadow-lg" alt="Wanderlust Team">
            </div>
        </div>
    </div>
</section>

<!-- Mission & Values Section -->
<section class="py-5 bg-cream-card border-top border-bottom border-gold">
    <div class="container py-4 text-center">
        <span class="section-subtitle">Core Pillars</span>
        <h2 class="section-title mb-5">Mission, Vision & Core Values</h2>

        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="icon-box-luxury">
                    <div class="icon-box-icon"><i class="fa-solid fa-compass"></i></div>
                    <h4 class="font-heading">Authentic Exploration</h4>
                    <p class="text-muted small">We connect travelers with authentic local cultures, heritage guardians, and private experiences inaccessible to standard tour groups.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="icon-box-luxury">
                    <div class="icon-box-icon"><i class="fa-solid fa-gem"></i></div>
                    <h4 class="font-heading">Uncompromising Quality</h4>
                    <p class="text-muted small">Every hotel room, transport vehicle, and private guide is personally audited to meet our 5-star quality guarantee.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="icon-box-luxury">
                    <div class="icon-box-icon"><i class="fa-solid fa-shield-halved"></i></div>
                    <h4 class="font-heading">Total Peace of Mind</h4>
                    <p class="text-muted small">From visa assistance to 24/7 emergency concierge support during your travel, we ensure complete confidence.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Leadership Team -->
<section class="py-5 bg-warm-ivory">
    <div class="container py-4">
        <div class="text-center mb-5">
            <span class="section-subtitle">Our Specialists</span>
            <h2 class="section-title">Meet Our Senior Travel Advisors</h2>
        </div>

        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="travel-card text-center">
                    <div class="travel-card-img-wrap" style="height: 250px;">
                        <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?q=80&w=400&auto=format&fit=crop" alt="Founder">
                    </div>
                    <div class="travel-card-body p-3">
                        <h4 class="font-heading fs-5 mb-1">Julian Sterling</h4>
                        <span class="text-gold small d-block mb-2">Founder & Managing Director</span>
                        <p class="text-muted small">15+ years in global luxury hospitality & aviation logistics.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="travel-card text-center">
                    <div class="travel-card-img-wrap" style="height: 250px;">
                        <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=400&auto=format&fit=crop" alt="Japan Specialist">
                    </div>
                    <div class="travel-card-body p-3">
                        <h4 class="font-heading fs-5 mb-1">Elena Rostova</h4>
                        <span class="text-gold small d-block mb-2">Head of East Asia Expeditions</span>
                        <p class="text-muted small">Specialist in Japan cultural heritage & Michelin dining reservations.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="travel-card text-center">
                    <div class="travel-card-img-wrap" style="height: 250px;">
                        <img src="https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?q=80&w=400&auto=format&fit=crop" alt="European Specialist">
                    </div>
                    <div class="travel-card-body p-3">
                        <h4 class="font-heading fs-5 mb-1">Tariq Mansoor</h4>
                        <span class="text-gold small d-block mb-2">Director of Alpine & Mountain Tours</span>
                        <p class="text-muted small">Specialist in Swiss Alps, Glacier Express, and Hunza Karakoram treks.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="travel-card text-center">
                    <div class="travel-card-img-wrap" style="height: 250px;">
                        <img src="https://images.unsplash.com/photo-1580489944761-15a19d654956?q=80&w=400&auto=format&fit=crop" alt="Visa Director">
                    </div>
                    <div class="travel-card-body p-3">
                        <h4 class="font-heading fs-5 mb-1">Sophia Al-Maktoum</h4>
                        <span class="text-gold small d-block mb-2">Senior Visa & Embassy Liaison</span>
                        <p class="text-muted small">Ex-consular officer specializing in Schengen and Middle East e-visas.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
