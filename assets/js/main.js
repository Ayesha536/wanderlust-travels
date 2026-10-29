/* ==========================================================================
   WANDERLUST TRAVELS - MAIN INTERACTIVE JAVASCRIPT
   ========================================================================== */

document.addEventListener('DOMContentLoaded', function() {
    
    // Initialize Wishlist Toggles
    const wishlistBtns = document.querySelectorAll('.card-wishlist-btn');
    wishlistBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const itemId = this.dataset.id;
            const itemType = this.dataset.type || 'package';
            const icon = this.querySelector('i');
            
            fetch('api/wishlist_toggle.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `item_id=${encodeURIComponent(itemId)}&item_type=${encodeURIComponent(itemType)}`
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    if (data.action === 'added') {
                        this.classList.add('active');
                        icon.classList.remove('fa-regular');
                        icon.classList.add('fa-solid');
                        showToast('Added to Wishlist!', 'Item saved to your personal dashboard.', 'success');
                    } else {
                        this.classList.remove('active');
                        icon.classList.remove('fa-solid');
                        icon.classList.add('fa-regular');
                        showToast('Removed from Wishlist', 'Item removed from your wishlist.', 'info');
                    }
                } else if (data.status === 'unauthorized') {
                    showToast('Login Required', 'Please log in to save items to your wishlist.', 'warning');
                    setTimeout(() => { window.location.href = 'login.php'; }, 1500);
                } else {
                    showToast('Notice', data.message || 'Action failed.', 'danger');
                }
            })
            .catch(err => {
                console.error(err);
                showToast('Error', 'Unable to complete request.', 'danger');
            });
        });
    });

    // Client-Side Package Filter logic (if on packages.php)
    const packageFilterForm = document.getElementById('packageFilterForm');
    if (packageFilterForm) {
        packageFilterForm.addEventListener('input', filterPackages);
    }

    function filterPackages() {
        const dest = document.getElementById('filterDestination')?.value.toLowerCase() || 'all';
        const type = document.getElementById('filterType')?.value.toLowerCase() || 'all';
        const maxBudget = parseFloat(document.getElementById('filterBudget')?.value) || 100000;
        
        const cards = document.querySelectorAll('.package-item-card');
        cards.forEach(card => {
            const cardDest = card.dataset.destination.toLowerCase();
            const cardType = card.dataset.type.toLowerCase();
            const cardPrice = parseFloat(card.dataset.price);

            const matchDest = (dest === 'all' || cardDest.includes(dest));
            const matchType = (type === 'all' || cardType.includes(type));
            const matchBudget = (cardPrice <= maxBudget);

            if (matchDest && matchType && matchBudget) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // Flights & Hotels Interactive Demo Simulator
    const flightSearchForm = document.getElementById('flightSearchForm');
    if (flightSearchForm) {
        flightSearchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const resultsContainer = document.getElementById('flightResultsContainer');
            const origin = document.getElementById('flightOrigin').value || 'New York (JFK)';
            const dest = document.getElementById('flightDest').value || 'Tokyo (NRT)';
            const date = document.getElementById('flightDate').value || '2026-11-10';

            resultsContainer.innerHTML = `
                <div class="text-center py-5">
                    <div class="spinner-border text-gold" role="status"></div>
                    <p class="mt-3 text-muted">Searching luxury flights from ${origin} to ${dest}...</p>
                </div>
            `;

            setTimeout(() => {
                resultsContainer.innerHTML = `
                    <div class="alert alert-light border border-gold d-flex align-items-center justify-content-between mb-4">
                        <div>
                            <span class="badge bg-gold me-2">Featured Route</span>
                            <strong>${origin} ➔ ${dest}</strong> | Date: <strong>${date}</strong>
                        </div>
                        <span class="text-muted">3 Direct Flight Options Available</span>
                    </div>

                    <!-- Flight Item 1 -->
                    <div class="card bg-cream-card border-gold mb-3 shadow-sm">
                        <div class="card-body p-4 d-md-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center mb-3 mb-md-0 me-md-4">
                                <div class="bg-dark-charcoal text-gold rounded-circle p-3 me-3">
                                    <i class="fa-solid fa-plane-departure fa-xl"></i>
                                </div>
                                <div>
                                    <h5 class="mb-1 text-main font-heading">Emirates • Flight EK-204</h5>
                                    <div class="text-muted small">Boeing 777-300ER • First & Business Class Suite</div>
                                </div>
                            </div>
                            <div class="text-md-center my-3 my-md-0 px-md-3">
                                <div class="fs-5 fw-bold">08:30 AM ➔ 04:15 PM</div>
                                <div class="badge bg-light text-dark border">Direct (11h 45m)</div>
                            </div>
                            <div class="text-md-end ms-md-4">
                                <div class="fs-4 font-heading text-gold fw-bold">$1,240 <span class="fs-6 text-muted font-sans">/ passenger</span></div>
                                <button class="btn btn-gold btn-sm mt-2 px-4" onclick="bookDemoItem('flight', 'Emirates Flight EK-204 (${origin} to ${dest})', 1240)">Select & Book</button>
                            </div>
                        </div>
                    </div>

                    <!-- Flight Item 2 -->
                    <div class="card bg-cream-card border-gold mb-3 shadow-sm">
                        <div class="card-body p-4 d-md-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center mb-3 mb-md-0 me-md-4">
                                <div class="bg-dark-charcoal text-gold rounded-circle p-3 me-3">
                                    <i class="fa-solid fa-plane fa-xl"></i>
                                </div>
                                <div>
                                    <h5 class="mb-1 text-main font-heading">ANA All Nippon Airways • Flight NH-011</h5>
                                    <div class="text-muted small">Boeing 787 Dreamliner • Premium Economy</div>
                                </div>
                            </div>
                            <div class="text-md-center my-3 my-md-0 px-md-3">
                                <div class="fs-5 fw-bold">11:15 AM ➔ 07:00 PM</div>
                                <div class="badge bg-light text-dark border">Direct (11h 45m)</div>
                            </div>
                            <div class="text-md-end ms-md-4">
                                <div class="fs-4 font-heading text-gold fw-bold">$1,450 <span class="fs-6 text-muted font-sans">/ passenger</span></div>
                                <button class="btn btn-gold btn-sm mt-2 px-4" onclick="bookDemoItem('flight', 'ANA Flight NH-011 (${origin} to ${dest})', 1450)">Select & Book</button>
                            </div>
                        </div>
                    </div>
                `;
            }, 800);
        });
    }

});

// Global Booking Function for Demo Flights & Hotels
function bookDemoItem(type, title, price) {
    fetch('api/book_flight_hotel.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `item_type=${encodeURIComponent(type)}&item_title=${encodeURIComponent(title)}&price=${encodeURIComponent(price)}`
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            showToast('Booking Successful!', `${title} has been booked! Check your dashboard.`, 'success');
            setTimeout(() => { window.location.href = 'dashboard.php?tab=bookings'; }, 1500);
        } else if (data.status === 'unauthorized') {
            showToast('Login Required', 'Please log in to complete your reservation.', 'warning');
            setTimeout(() => { window.location.href = 'login.php'; }, 1500);
        } else {
            showToast('Booking Error', data.message || 'Could not complete booking.', 'danger');
        }
    });
}

// Global Toast Notification Helper
function showToast(title, message, type = 'info') {
    let container = document.getElementById('toastContainer');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toastContainer';
        container.className = 'toast-container position-fixed bottom-0 end-0 p-3';
        container.style.zIndex = '9999';
        document.body.appendChild(container);
    }

    const toastId = 'toast_' + Date.now();
    const bgClass = type === 'success' ? 'bg-success text-white' : type === 'warning' ? 'bg-warning text-dark' : type === 'danger' ? 'bg-danger text-white' : 'bg-dark text-white';

    const toastHTML = `
        <div id="${toastId}" class="toast align-items-center ${bgClass} border-0 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <strong>${title}</strong><br>
                    <small>${message}</small>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', toastHTML);
    const toastEl = document.getElementById(toastId);
    const toast = new bootstrap.Toast(toastEl, { delay: 4000 });
    toast.show();
}
