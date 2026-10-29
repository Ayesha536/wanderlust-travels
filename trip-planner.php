<?php
// trip-planner.php - Interactive Trip Planner & Itinerary Builder
$page_title = "Interactive Trip Planner";
require_once __DIR__ . '/includes/header.php';

$preselect_dest = isset($_GET['dest']) ? sanitize($_GET['dest']) : 'Japan';
?>

<!-- Header Banner -->
<section class="py-5 bg-dark-charcoal text-white text-center border-bottom border-gold">
    <div class="container py-4">
        <span class="section-subtitle">Tailor-Made Journeys</span>
        <h1 class="font-heading display-4 fw-bold mb-3 text-white">Interactive Trip Planner</h1>
        <p class="text-light opacity-75 mx-auto" style="max-width: 650px;">
            Design your ideal custom trip by combining destinations, five-star hotel tiers, signature experiences, and custom daily activities.
        </p>
    </div>
</section>

<!-- Interactive Planner Application -->
<section class="py-5 bg-warm-ivory">
    <div class="container">
        <div class="row g-4">
            <!-- Left Column: Form Controls -->
            <div class="col-lg-7">
                <div class="card bg-cream-card border-gold p-4 shadow-sm mb-4">
                    <h3 class="font-heading text-main mb-4 border-bottom border-gold pb-2"><i class="fa-solid fa-sliders text-gold me-2"></i>Configure Trip Details</h3>
                    
                    <form id="tripPlannerForm">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label small fw-bold text-uppercase text-muted">Trip Title</label>
                                <input type="text" id="plannerTitle" class="form-control form-control-luxury" value="Grand Expedition 2026">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-uppercase text-muted">Destination Country</label>
                                <select id="plannerDest" class="form-select form-control-luxury" onchange="generateItineraryPreview()">
                                    <option value="Japan" <?php echo ($preselect_dest === 'Japan') ? 'selected' : ''; ?>>Japan (Kyoto & Tokyo)</option>
                                    <option value="Turkey" <?php echo ($preselect_dest === 'Turkey') ? 'selected' : ''; ?>>Turkey (Cappadocia & Istanbul)</option>
                                    <option value="UAE" <?php echo ($preselect_dest === 'UAE' || $preselect_dest === 'Dubai') ? 'selected' : ''; ?>>UAE (Dubai & Desert)</option>
                                    <option value="Switzerland" <?php echo ($preselect_dest === 'Switzerland') ? 'selected' : ''; ?>>Switzerland (Swiss Alps)</option>
                                    <option value="China" <?php echo ($preselect_dest === 'China') ? 'selected' : ''; ?>>China (Great Wall & Beijing)</option>
                                    <option value="Pakistan" <?php echo ($preselect_dest === 'Pakistan') ? 'selected' : ''; ?>>Pakistan (Hunza & Karakoram)</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-uppercase text-muted">Number of Guests</label>
                                <input type="number" id="plannerGuests" class="form-control form-control-luxury" value="2" min="1" max="10" onchange="generateItineraryPreview()">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-uppercase text-muted">Start Date</label>
                                <input type="date" id="plannerStartDate" class="form-control form-control-luxury" value="2026-11-15" min="<?php echo date('Y-m-d'); ?>" onchange="generateItineraryPreview()">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-uppercase text-muted">End Date (Duration)</label>
                                <input type="date" id="plannerEndDate" class="form-control form-control-luxury" value="2026-11-22" min="<?php echo date('Y-m-d'); ?>" onchange="generateItineraryPreview()">
                            </div>

                            <div class="col-12">
                                <label class="form-label small fw-bold text-uppercase text-muted">Preferred Hotel Tier</label>
                                <select id="plannerHotelTier" class="form-select form-control-luxury" onchange="generateItineraryPreview()">
                                    <option value="5-Star Palace Suite">5-Star Palace Suite ($800/night)</option>
                                    <option value="Boutique Luxury Cave & Lodge" selected>Boutique Heritage Lodge ($450/night)</option>
                                    <option value="4-Star Premium Resort">4-Star Premium Resort ($280/night)</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label small fw-bold text-uppercase text-muted">Included Experiences</label>
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <div class="form-check p-3 bg-light rounded border border-gold">
                                            <input class="form-check-input exp-checkbox" type="checkbox" value="Private Sunrise Hot Air Balloon" id="exp1" checked data-price="260" onchange="generateItineraryPreview()">
                                            <label class="form-check-label small fw-bold" for="exp1">Sunrise Hot Air Balloon (+$260)</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check p-3 bg-light rounded border border-gold">
                                            <input class="form-check-input exp-checkbox" type="checkbox" value="Private Tea Ceremony & Kaiseki" id="exp2" checked data-price="380" onchange="generateItineraryPreview()">
                                            <label class="form-check-label small fw-bold" for="exp2">Private Tea Ceremony (+$380)</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check p-3 bg-light rounded border border-gold">
                                            <input class="form-check-input exp-checkbox" type="checkbox" value="Private Sunset Yacht Cruise" id="exp3" data-price="550" onchange="generateItineraryPreview()">
                                            <label class="form-check-label small fw-bold" for="exp3">Private Yacht Cruise (+$550)</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check p-3 bg-light rounded border border-gold">
                                            <input class="form-check-input exp-checkbox" type="checkbox" value="VIP Helicopter Alpine Flight" id="exp4" data-price="720" onchange="generateItineraryPreview()">
                                            <label class="form-check-label small fw-bold" for="exp4">Helicopter Alpine Tour (+$720)</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Column: Live Day-by-Day Itinerary Preview -->
            <div class="col-lg-5">
                <div class="card bg-dark-charcoal text-white border-gold p-4 sticky-top shadow-lg" style="top: 100px;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="font-heading text-gold mb-0"><i class="fa-solid fa-route me-2"></i>Trip Summary</h4>
                        <span class="badge bg-gold text-dark" id="calculatedDaysBadge">7 Days</span>
                    </div>

                    <div class="bg-navy-card p-3 rounded mb-4 border border-secondary text-center">
                        <small class="text-muted text-uppercase d-block mb-1">Estimated Total Cost</small>
                        <div class="fs-2 font-heading text-gold fw-bold" id="calculatedTotalDisplay">$3,450</div>
                        <small class="text-muted font-sans" id="perPersonDisplay">($1,725 per guest)</small>
                    </div>

                    <!-- Day-by-day Itinerary Box -->
                    <h5 class="font-heading text-gold fs-6 mb-2">Generated Day-by-Day Schedule</h5>
                    <div id="itineraryTimelineBox" class="mb-4 text-start font-sans" style="max-height: 280px; overflow-y: auto;">
                        <!-- Dynamic content injected by JavaScript -->
                    </div>

                    <button class="btn btn-gold w-100 py-3 fw-bold fs-6 mb-2" onclick="saveCustomTrip()">
                        <i class="fa-solid fa-floppy-disk me-2"></i>Save Trip to My Account
                    </button>

                    <button class="btn btn-outline-light w-100 py-2" onclick="showToast('Quote Requested', 'A senior concierge agent will review your custom trip within 2 hours.', 'success')">
                        <i class="fa-solid fa-paper-plane me-1"></i>Request Custom Quote
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function generateItineraryPreview() {
    const dest = document.getElementById('plannerDest').value;
    const guests = parseInt(document.getElementById('plannerGuests').value) || 2;
    const startDate = new Date(document.getElementById('plannerStartDate').value || '2026-11-15');
    const endDate = new Date(document.getElementById('plannerEndDate').value || '2026-11-22');

    const diffTime = Math.abs(endDate - startDate);
    const diffDays = Math.max(1, Math.ceil(diffTime / (1000 * 60 * 60 * 24)));
    
    document.getElementById('calculatedDaysBadge').textContent = diffDays + ' Days';

    // Hotel cost calc
    const hotelTier = document.getElementById('plannerHotelTier').value;
    let hotelRate = 450;
    if (hotelTier.includes('800')) hotelRate = 800;
    if (hotelTier.includes('280')) hotelRate = 280;

    let expTotal = 0;
    const selectedExps = [];
    document.querySelectorAll('.exp-checkbox:checked').forEach(cb => {
        expTotal += parseFloat(cb.dataset.price);
        selectedExps.push(cb.value);
    });

    const hotelTotal = diffDays * hotelRate;
    const baseGuideCost = diffDays * 150;
    const totalCost = (hotelTotal + baseGuideCost + (expTotal * guests));
    const perPerson = Math.round(totalCost / guests);

    document.getElementById('calculatedTotalDisplay').textContent = '$' + totalCost.toLocaleString();
    document.getElementById('perPersonDisplay').textContent = '($' + perPerson.toLocaleString() + ' per guest)';

    // Build timeline UI
    const timelineBox = document.getElementById('itineraryTimelineBox');
    let html = '';

    for (let i = 1; i <= diffDays; i++) {
        let dayActivity = `Day ${i}: Sightseeing in ${dest} & luxury accommodation stay`;
        if (i === 1) dayActivity = `Day 1: Chauffeur arrival in ${dest} & 5-Star Hotel Check-in`;
        else if (i === diffDays) dayActivity = `Day ${diffDays}: Breakfast & VIP Airport Transfer`;
        else if (selectedExps.length > 0 && i <= selectedExps.length + 1) {
            dayActivity = `Day ${i}: ${selectedExps[i-2] || 'Guided Heritage Tour'}`;
        }

        html += `
            <div class="p-2 mb-2 bg-dark rounded border border-secondary small text-light">
                <strong class="text-gold">Day ${i}:</strong> ${dayActivity}
            </div>
        `;
    }

    timelineBox.innerHTML = html;
}

function saveCustomTrip() {
    const title = document.getElementById('plannerTitle').value || 'My Bespoke Journey';
    const dest = document.getElementById('plannerDest').value;
    const startDate = document.getElementById('plannerStartDate').value;
    const endDate = document.getElementById('plannerEndDate').value;
    const hotelTier = document.getElementById('plannerHotelTier').value;
    const guests = document.getElementById('plannerGuests').value;
    const totalDisplay = document.getElementById('calculatedTotalDisplay').textContent.replace('$', '').replace(',', '');
    
    const itineraryHtml = document.getElementById('itineraryTimelineBox').innerHTML;

    fetch('api/save_trip.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `trip_title=${encodeURIComponent(title)}&destination=${encodeURIComponent(dest)}&start_date=${encodeURIComponent(startDate)}&end_date=${encodeURIComponent(endDate)}&hotel_tier=${encodeURIComponent(hotelTier)}&guests=${encodeURIComponent(guests)}&estimated_budget=${encodeURIComponent(totalDisplay)}&itinerary_json=${encodeURIComponent(itineraryHtml)}`
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            showToast('Trip Saved!', data.message, 'success');
            setTimeout(() => { window.location.href = 'dashboard.php?tab=trips'; }, 1500);
        } else if (data.status === 'unauthorized') {
            showToast('Login Required', 'Please log in to save custom trips to your account.', 'warning');
            setTimeout(() => { window.location.href = 'login.php'; }, 1500);
        } else {
            showToast('Error', data.message || 'Could not save trip.', 'danger');
        }
    });
}

document.addEventListener('DOMContentLoaded', generateItineraryPreview);
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
