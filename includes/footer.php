<?php
// includes/footer.php - Reusable Footer Component
?>
<!-- Luxury Footer Section -->
<footer class="footer-wanderlust mt-5">
    <div class="container">
        <div class="row g-4 mb-5">
            <!-- Brand Column -->
            <div class="col-lg-4 col-md-6">
                <div class="d-flex align-items-center mb-3">
                    <i class="fa-solid fa-earth-americas text-gold fs-2 me-2"></i>
                    <div class="footer-brand lh-1">Wander<span class="text-gold fw-bold">lust</span></div>
                </div>
                <p class="text-muted small pe-lg-4 mb-4">
                    Wanderlust Travels is an international luxury travel agency dedicated to crafting bespoke journeys, curated tour packages, five-star hotel stays, and seamless visa assistance worldwide.
                </p>
                <div class="text-gold font-heading fs-5 fw-bold mb-3">Explore • Experience • Belong</div>
                <div class="d-flex gap-3 fs-5 text-gold">
                    <a href="#" class="text-gold hover-opacity"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" class="text-gold hover-opacity"><i class="fa-brands fa-facebook"></i></a>
                    <a href="#" class="text-gold hover-opacity"><i class="fa-brands fa-x-twitter"></i></a>
                    <a href="#" class="text-gold hover-opacity"><i class="fa-brands fa-linkedin"></i></a>
                    <a href="#" class="text-gold hover-opacity"><i class="fa-brands fa-youtube"></i></a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6">
                <h5 class="font-heading text-white mb-3 text-gold">Quick Links</h5>
                <a href="destinations.php" class="footer-link">Destinations</a>
                <a href="packages.php" class="footer-link">Tour Packages</a>
                <a href="flights-hotels.php" class="footer-link">Flights & Hotels</a>
                <a href="visa.php" class="footer-link">Visa Assistance</a>
                <a href="experiences.php" class="footer-link">Experiences</a>
                <a href="trip-planner.php" class="footer-link">Trip Planner</a>
            </div>

            <!-- Global Destinations -->
            <div class="col-lg-3 col-md-6">
                <h5 class="font-heading text-white mb-3 text-gold">Featured Destinations</h5>
                <a href="destination-detail.php?id=1" class="footer-link">Japan (Kyoto & Tokyo)</a>
                <a href="destination-detail.php?id=2" class="footer-link">Turkey (Cappadocia & Istanbul)</a>
                <a href="destination-detail.php?id=3" class="footer-link">UAE (Dubai & Desert Oasis)</a>
                <a href="destination-detail.php?id=4" class="footer-link">Switzerland (Swiss Alps)</a>
                <a href="destination-detail.php?id=5" class="footer-link">China (Great Wall & Beijing)</a>
                <a href="destination-detail.php?id=6" class="footer-link">Pakistan (Hunza Valley)</a>
            </div>

            <!-- Newsletter & Contact -->
            <div class="col-lg-3 col-md-6">
                <h5 class="font-heading text-white mb-3 text-gold">Bespoke Newsletter</h5>
                <p class="text-muted small mb-3">
                    Subscribe to receive private travel invitations, early tour access, and seasonal guides.
                </p>
                <form onsubmit="event.preventDefault(); showToast('Subscribed!', 'Thank you for joining Wanderlust Travels newsletter.', 'success');">
                    <div class="input-group mb-3">
                        <input type="email" class="form-control form-control-luxury bg-dark text-white border-secondary" placeholder="Enter your email" required>
                        <button class="btn btn-gold" type="submit"><i class="fa-solid fa-paper-plane"></i></button>
                    </div>
                </form>
                <div class="small text-muted mt-3">
                    <i class="fa-solid fa-headset me-2 text-gold"></i>24/7 VIP Concierge Support
                </div>
            </div>
        </div>

        <hr class="border-secondary my-4">

        <div class="row align-items-center small text-muted">
            <div class="col-md-6 text-center text-md-start mb-2 mb-md-0">
                &copy; <?php echo date('Y'); ?> <strong>Wanderlust Travels International</strong>. All Rights Reserved.
            </div>
            <div class="col-md-6 text-center text-md-end">
                <a href="#" class="text-muted text-decoration-none me-3">Privacy Policy</a>
                <a href="#" class="text-muted text-decoration-none me-3">Terms of Service</a>
                <a href="#" class="text-muted text-decoration-none">Cookie Settings</a>
            </div>
        </div>
    </div>
</footer>

<!-- Toast Notification Container -->
<div id="toastContainer" class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 9999;"></div>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/bootstrap.bundle.min.js"></script>
<!-- Custom JavaScript -->
<script src="assets/js/main.js"></script>

</body>
</html>
