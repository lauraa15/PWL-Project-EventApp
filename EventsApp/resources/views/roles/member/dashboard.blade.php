@extends('layouts-horizontal.app')

@section('title', 'Horizontal Layout - Mazer Admin Dashboard')

@push('styles')
    <style>
        .stats-icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
        }

        .stats-icon.purple {
            background-color: #6f42c1;
            color: white;
        }

        .stats-icon.blue {
            background-color: #0d6efd;
            color: white;
        }

        .stats-icon.green {
            background-color: #198754;
            color: white;
        }

        .stats-icon.red {
            background-color: #dc3545;
            color: white;
        }

        .event-card {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }

        .event-card:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .event-status {
            font-size: 0.875rem;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: 500;
        }

        .status-registered {
            background-color: #e3f2fd;
            color: #1976d2;
        }

        .status-attended {
            background-color: #e8f5e8;
            color: #388e3c;
        }

        .badge {
            font-size: 0.75rem;
        }

        .table th {
            font-weight: 600;
            color: #495057;
            border-bottom: 2px solid #dee2e6;
        }

        .btn-sm {
            padding: 0.375rem 0.75rem;
        }

        .text-truncate {
            max-width: 200px;
        }
    </style>
@endpush

@section('content')
    <div class="page-content">
        <!-- Stats Cards -->
        <section class="row mb-4">
            <div class="col-6 col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                <div class="stats-icon purple mb-2">
                                    <i class="iconly-boldTicket"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Total Events</h6>
                                <h6 class="font-extrabold mb-0" id="totalEvents">-</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                <div class="stats-icon blue mb-2">
                                    <i class="iconly-boldProfile"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Registered</h6>
                                <h6 class="font-extrabold mb-0" id="registeredEvents">-</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                <div class="stats-icon green mb-2">
                                    <i class="iconly-boldBookmark"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Attended</h6>
                                <h6 class="font-extrabold mb-0" id="attendedEvents">-</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                <div class="stats-icon red mb-2">
                                    <i class="iconly-boldCalendar"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Upcoming</h6>
                                <h6 class="font-extrabold mb-0" id="upcomingEvents">-</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main Content -->
        <section class="row">
            <div class="col-12 col-lg-8">
                <!-- Discover Events Section -->
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4>Discover Events</h4>
                            <div class="d-flex gap-2">
                                <select class="form-select form-select-sm" id="typeFilter" style="width: auto;">
                                    <option value="">All Categories</option>
                                    <option value="music">Music</option>
                                    <option value="sports">Sports</option>
                                    <option value="technology">Technology</option>
                                    <option value="business">Business</option>
                                    <option value="arts">Arts & Culture</option>
                                </select>
                                <button class="btn btn-sm btn-outline-primary" onclick="refreshEvents()">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="discoverEventsTable">
                                <thead>
                                    <tr>
                                        <th>Event</th>
                                        <th>Date</th>
                                        <th>Location</th>
                                        <th>Type</th>
                                        <th>Price</th>
                                        <th>Capacity</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="discoverEventsBody">
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <nav aria-label="Page navigation" class="mt-3">
                            <ul class="pagination justify-content-center" id="discoverPagination">
                                <!-- Pagination akan di-generate oleh JavaScript -->
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <!-- Your Events Section -->
                <div class="card">
                    <div class="card-header">
                        <h4>Your Events</h4>
                        <p class="text-muted small mb-0">Events you've registered for</p>
                    </div>
                    <div class="card-body">
                        <div id="yourEventsContainer">
                            <div class="text-center">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Upload Receipt Modal -->
                <div class="modal fade" id="uploadReceiptModal" tabindex="-1" aria-labelledby="uploadReceiptModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="uploadReceiptModalLabel">Upload Payment Receipt</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="uploadReceiptForm" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label for="receiptFile" class="form-label">Select Receipt Image</label>
                                        <input type="file" class="form-control" id="receiptFile" name="receipt" accept="image/*" required>
                                        <div class="form-text">Please upload a clear image of your payment receipt (JPG, PNG, max 5MB)</div>
                                    </div>
                                    <div class="mb-3">
                                        <div id="imagePreview" class="d-none">
                                            <img id="previewImg" src="" alt="Receipt Preview" class="img-fluid rounded" style="max-height: 200px;">
                                        </div>
                                    </div>
                                    <input type="hidden" id="eventIdForUpload" name="event_id">
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-primary" id="submitReceiptBtn" onclick="uploadReceipt()">
                                    <span id="uploadSpinner" class="spinner-border spinner-border-sm d-none me-2" role="status"></span>
                                    Upload Receipt
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- QR Code Modal -->
                <div class="modal fade" id="qrCodeModal" tabindex="-1" aria-labelledby="qrCodeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="qrCodeModalLabel">Event QR Code</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-center">
                                <div id="qrCodeContainer">
                                    <!-- QR Code will be displayed here -->
                                </div>
                                <p class="mt-3 text-muted small">Show this QR code at the event entrance</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    </div>

    <!-- Event Registration Modal -->
    <div class="modal fade" id="registerEventModal" tabindex="-1" aria-labelledby="registerEventModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="registerEventModalLabel">Register for Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="eventDetails">
                        <!-- Event details will be populated here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmRegisterBtn">Register Now</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Event Details Modal -->
    <div class="modal fade" id="eventDetailsModal" tabindex="-1" aria-labelledby="eventDetailsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventDetailsModalLabel">Event Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="eventDetailsContent">
                    <!-- Event details will be populated here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/extensions/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/static/js/pages/dashboard.js') }}"></script>
    <script>
        const sampleEvents = @json($events, JSON_UNESCAPED_UNICODE);
        const eventTypes = @json($eventTypes, JSON_UNESCAPED_UNICODE);
        const eventPayments = @json($eventPayments, JSON_UNESCAPED_UNICODE);

        let registeredEvents = JSON.parse(localStorage.getItem('registeredEvents') || '[]');
        let currentPage = 1;
        const eventsPerPage = 10;

        // Format currency
        function formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(amount);
        }

        // Format date
        function formatDate(dateString) {
            return new Date(dateString).toLocaleDateString('id-ID', {
                weekday: 'short',
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
        }

        // Load discover events
        function loadDiscoverEvents(page = 1, type = '') {
            let filteredEvents = sampleEvents;

            if (type) {
                filteredEvents = sampleEvents.filter(event => event.event_type_name === type);
            }

            const startIndex = (page - 1) * eventsPerPage;
            const endIndex = startIndex + eventsPerPage;
            const paginatedEvents = filteredEvents.slice(startIndex, endIndex);

            const tbody = document.getElementById('discoverEventsBody');

            if (paginatedEvents.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">No events found</td></tr>';
                return;
            }

            tbody.innerHTML = paginatedEvents.map(event => {
                const isRegistered = registeredEvents.some(reg => reg.id === event.id);
                const availableSpots = event.max_participants - (event.current_participant === undefined || event.current_participant === null ? 0 : event.current_participant);

                return `
            <tr>
                <td>
                    <div>
                        <h6 class="mb-1 text-truncate">${event.name}</h6>
                        <small class="text-muted">${event.organizer}</small>
                    </div>
                </td>
                <td>
                    <div>
                        <div class="fw-bold">
                            ${event.start_date && event.end_date
                                ? `${formatDate(event.start_date)} - ${formatDate(event.end_date)}`
                                : formatDate(event.date)}
                        </div>
                    </div>


                </td>
                <td class="text-truncate">${event.location}</td>
                <td><span class="badge bg-primary">${event.event_type_name}</span></td>
                <td class="fw-bold">${event.registration_fee === 0 ? 'Free' : formatCurrency(event.registration_fee)}</td>
                <td>
                    <div>
                        <small class="text-muted">${event.current_participant === undefined || event.current_participant === null ? 0 : event.current_participant}/${event.max_participants}</small>
                        <div class="progress mt-1" style="height: 4px;">
                            <div class="progress-bar" role="progressbar" style="width: ${((event.current_participant === undefined || event.current_participant === null ? 0 : event.current_participant)/event.max_participants)*100}%"></div>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="d-flex gap-1">
                        <button class="btn btn-sm btn-outline-primary" onclick="viewEventDetails(${event.id})" title="View Details">
                            <i class="bi bi-eye"></i>
                        </button>
                        ${isRegistered ?
                            '<button class="btn btn-sm btn-success" disabled><i class="bi bi-check"></i></button>' :
                            availableSpots > 0 ?
                                `<button class="btn btn-sm btn-primary" onclick="registerForEvent(${event.id})" title="Register"><i class="bi bi-plus"></i></button>` :
                                '<button class="btn btn-sm btn-secondary" disabled title="Full"><i class="bi bi-x"></i></button>'
                        }
                    </div>
                </td>
            </tr>
        `;
            }).join('');

            // Update pagination
            updatePagination(filteredEvents.length, page);
        }

        // Load your events (Modified version)
        function loadYourEvents() {
            const container = document.getElementById('yourEventsContainer');
            if (registeredEvents.length === 0) {
                container.innerHTML = `
                    <div class="text-center text-muted">
                        <i class="bi bi-calendar-x" style="font-size: 3rem; opacity: 0.5;"></i>
                        <p class="mt-2">No events registered yet</p>
                        <small>Browse events and register for your first event!</small>
                    </div>
                `;
                return;
            }

            container.innerHTML = registeredEvents.map(event => {
                const now = new Date();
                const isPast = event.end_date < now;

                const payment = eventPayments.find(p => p.event_id === event.id);
                const paymentStatus = payment?.payment_status || 'pending';

                return `
                    <div class="event-card">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="mb-1">${event.name}</h6>
                            <span class="event-status ${isPast ? 'status-attended' : 'status-registered'}">
                                ${isPast ? 'Attended' : 'Registered'}
                            </span>
                        </div>
                        <div class="small text-muted mb-2">
                            <i class="bi bi-calendar me-1"></i>${event.start_date && event.end_date
                                ? `${formatDate(event.start_date)} - ${formatDate(event.end_date)}`
                                : formatDate(event.date)}
                        </div>
                        <div class="small text-muted mb-2">
                            <i class="bi bi-geo-alt me-1"></i>${event.location}
                        </div>
                        <div class="small mb-2">
                            <span class="badge ${getPaymentStatusBadge(paymentStatus)}">
                                Payment: ${paymentStatus.charAt(0).toUpperCase() + paymentStatus.slice(1)}
                            </span>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-outline-primary flex-fill" onclick="viewEventDetails(${event.id})">
                                <i class="bi bi-eye me-1"></i>Details
                            </button>
                            ${!isPast ? getEventActionButtons({ ...event, payment_status: paymentStatus }) : ''}
                        </div>
                    </div>
                `;
            }).join('');
        }

        // Update stats
        function updateStats() {
            const totalEvents = sampleEvents.length;
            const registeredCount = registeredEvents.length;
            const attendedCount = registeredEvents.filter(event => new Date(event.end_date) < new Date()).length;
            const upcomingCount = registeredEvents.filter(event => new Date(event.start_date) >= new Date()).length;

            document.getElementById('totalEvents').textContent = totalEvents;
            document.getElementById('registeredEvents').textContent = registeredCount;
            document.getElementById('attendedEvents').textContent = attendedCount;
            document.getElementById('upcomingEvents').textContent = upcomingCount;
        }

        // Update pagination
        function updatePagination(totalEvents, currentPage) {
            const totalPages = Math.ceil(totalEvents / eventsPerPage);
            const pagination = document.getElementById('discoverPagination');

            if (totalPages <= 1) {
                pagination.innerHTML = '';
                return;
            }

            let paginationHTML = '';

            // Previous button
            paginationHTML += `
        <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="changePage(${currentPage - 1})">Previous</a>
        </li>
    `;

            // Page numbers
            for (let i = 1; i <= totalPages; i++) {
                paginationHTML += `
            <li class="page-item ${i === currentPage ? 'active' : ''}">
                <a class="page-link" href="#" onclick="changePage(${i})">${i}</a>
            </li>
        `;
            }

            // Next button
            paginationHTML += `
        <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="changePage(${currentPage + 1})">Next</a>
        </li>
    `;

            pagination.innerHTML = paginationHTML;
        }

        // Change page
        function changePage(page) {
            const type = document.getElementById('typeFilter').value;
            loadDiscoverEvents(page, type);
            currentPage = page;
        }

        // View event details
        function viewEventDetails(eventId) {
            const event = sampleEvents.find(e => e.id === eventId);
            if (!event) return;

            const isRegistered = registeredEvents.some(reg => reg.id === eventId);
            const availableSpots = event.max_participants - (event.current_participant === undefined || event.current_participant === null ? 0 : event.current_participant);

            document.getElementById('eventDetailsContent').innerHTML = `
        <div class="row">
            <div class="col-md-8">
                <h4>${event.name}</h4>
                <p class="text-muted mb-3">${event.description}</p>

                <div class="row mb-3">
                    <div class="col-sm-6">
                        <strong>Date & Time:</strong><br>
                        ${event.start_date && event.end_date
                            ? `${formatDate(event.start_date)} - ${formatDate(event.end_date)}`
                            : formatDate(event.date)}
                    </div>
                    <div class="col-sm-6">
                        <strong>Location:</strong><br>
                        ${event.location}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-6">
                        <strong>Type:</strong><br>
                        <span class="badge bg-primary">${event.event_type_name}</span>
                    </div>
                    <div class="col-sm-6">
                        <strong>Organizer:</strong><br>
                        ${event.organizer}
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <strong>Price:</strong><br>
                        <span class="h5 text-success">${event.registration_fee === 0 ? 'Free' : formatCurrency(event.registration_fee)}</span>
                    </div>
                    <div class="col-sm-6">
                        <strong>Availability:</strong><br>
                        ${availableSpots} spots left
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h5>Capacity</h5>
                            <div class="h2">${event.current_participant === undefined || event.current_participant === null ? 0 : event.current_participant}/${event.max_participants}</div>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width: ${((event.current_participant === undefined || event.current_participant === null ? 0 : event.current_participant)/event.max_participants)*100}%"></div>
                            </div>
                        </div>

                        ${isRegistered ?
                            '<button class="btn btn-success w-100" disabled><i class="bi bi-check me-2"></i>Registered</button>' :
                            availableSpots > 0 ?
                                `<button class="btn btn-primary w-100" onclick="registerForEvent(${event.id}); bootstrap.Modal.getInstance(document.getElementById('eventDetailsModal')).hide();"><i class="bi bi-plus me-2"></i>Register Now</button>` :
                                '<button class="btn btn-secondary w-100" disabled><i class="bi bi-x me-2"></i>Event Full</button>'
                        }
                    </div>
                </div>
            </div>
        </div>
    `;

            new bootstrap.Modal(document.getElementById('eventDetailsModal')).show();
        }

        // Register for event
        function registerForEvent(eventId) {
            const event = sampleEvents.find(e => e.id === eventId);
            if (!event) return;

            // Cek jika sudah terdaftar
            if (registeredEvents.some(reg => reg.id === eventId)) {
                alert('You are already registered for this event!');
                return;
            }

            // Tampilkan isi ke modal
            document.getElementById('eventDetails').innerHTML = `
                <div class="card">
                    <div class="card-body">
                        <h5>${event.name}</h5>
                        <p class="text-muted mb-2">${event.description}</p>
                        <div class="row">
                            <div class="col-6">
                                <strong>Date:</strong> ${
                                    event.start_date && event.end_date
                                        ? `${formatDate(event.start_date)} - ${formatDate(event.end_date)}`
                                        : formatDate(event.date)
                                }
                            </div>
                            <div class="col-6">
                                <strong>Price:</strong> ${event.registration_fee === 0 ? 'Free' : formatCurrency(event.registration_fee)}
                            </div>
                        </div>
                    </div>
                </div>
            `;

            // Tombol Submit
            document.getElementById('confirmRegisterBtn').onclick = async () => {

                try {
                    const response = await fetch(`http://localhost:3000/api/registrations/${eventId}/register`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        }
                    });

                    const result = await response.json();

                    if (result.success) {
                        alert('Successfully registered for the event!');

                        // Optional: Tambahkan ke localStorage agar UI berubah
                        registeredEvents.push({
                            ...event,
                            registeredAt: new Date().toISOString()
                        });
                        localStorage.setItem('registeredEvents', JSON.stringify(registeredEvents));

                        loadDiscoverEvents(currentPage, document.getElementById('typeFilter').value);
                        loadYourEvents();
                        updateStats();

                        bootstrap.Modal.getInstance(document.getElementById('registerEventModal')).hide();
                    } else {
                        alert(`Registration failed: ${result.error || 'Unknown error'}`);
                    }
                } catch (error) {
                    console.error(error);
                    alert('An error occurred while submitting the registration.');
                }
            };

            // Tampilkan modal
            new bootstrap.Modal(document.getElementById('registerEventModal')).show();
        }

        // Refresh events
        function refreshEvents() {
            loadDiscoverEvents(currentPage, document.getElementById('typeFilter').value);
            loadYourEvents();
            updateStats();
        }

        // Quick actions
        // function showEventHistory() {
        //     alert('Event history feature coming soon!');
        // }

        // function showFavorites() {
        //     alert('Favorites feature coming soon!');
        // }

        // function showProfile() {
        //     alert('Profile settings feature coming soon!');
        // }

        // Event listeners
        document.getElementById('typeFilter').addEventListener('change', function() {
            currentPage = 1;
            loadDiscoverEvents(1, this.value);
        });

        // Initialize dashboard
        document.addEventListener('DOMContentLoaded', function() {
            // Check authentication (modify as needed)
            const token = localStorage.getItem('token');
            if (!token) {
                window.location.href = '/login';
                return;
            }

            // Load initial data
            loadDiscoverEvents();
            loadYourEvents();
            updateStats();
        });

        // Helper function to get payment status badge class
        function getPaymentStatusBadge(status) {
            switch(status) {
                case 'verified': return 'bg-success';
                case 'pending': return 'bg-warning';
                case 'rejected': return 'bg-danger';
                case 'on-progress': return 'bg-secondary';
                default: return 'bg-secondary';
            }
        }

        // Helper function to get event action buttons based on payment status
        function getEventActionButtons(event) {
            const paymentStatus = event?.payment_status || 'pending';

            switch(paymentStatus) {
                case 'pending':
                    return `<button class="btn btn-sm btn-outline-success" onclick="openUploadModal(${event.id})" title="Upload Receipt">
                                <i class="bi bi-cloud-upload me-1"></i>Upload Receipt
                            </button>`;
                case 'verified':
                    return `<button class="btn btn-sm btn-success" onclick="showQRCode(${event.id})" title="Show QR Code">
                                <i class="bi bi-qr-code me-1"></i>Show QR
                            </button>`;
                case 'rejected':
                    return `<button class="btn btn-sm btn-outline-warning" onclick="openUploadModal(${event.id})" title="Re-upload Receipt">
                                <i class="bi bi-arrow-clockwise me-1"></i>Re-upload
                            </button>`;
                case 'on-progress':
                return `<button class="btn btn-sm btn-outline-info" disabled title="Your receipt is being reviewed">
                            <i class="bi bi-hourglass-split me-1"></i>In Review
                        </button>`;
                default:
                    return '';
            }
        }

        // Open upload receipt modal
        function openUploadModal(eventId) {
            document.getElementById('eventIdForUpload').value = eventId;
            document.getElementById('uploadReceiptForm').reset();
            document.getElementById('imagePreview').classList.add('d-none');

            const modal = new bootstrap.Modal(document.getElementById('uploadReceiptModal'));
            modal.show();
        }

        // Preview image before upload
        document.getElementById('receiptFile').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validate file size (5MB max)
                if (file.size > 5 * 1024 * 1024) {
                    alert('File size too large. Please select an image under 5MB.');
                    e.target.value = '';
                    return;
                }

                // Validate file type
                if (!file.type.startsWith('image/')) {
                    alert('Please select a valid image file.');
                    e.target.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImg').src = e.target.result;
                    document.getElementById('imagePreview').classList.remove('d-none');
                };
                reader.readAsDataURL(file);
            }
        });

        // Upload receipt function - sends POST to Node.js backend
        async function uploadReceipt() {
            const form = document.getElementById('uploadReceiptForm');
            const fileInput = document.getElementById('receiptFile');
            const eventId = document.getElementById('eventIdForUpload').value;
            const submitBtn = document.getElementById('submitReceiptBtn');
            const spinner = document.getElementById('uploadSpinner');

            if (!fileInput.files[0]) {
                alert('Please select a receipt image.');
                return;
            }

            // Show loading state
            submitBtn.disabled = true;
            spinner.classList.remove('d-none');

            try {
                const formData = new FormData();
                formData.append('receipt', fileInput.files[0]);
                formData.append('event_id', eventId);
                formData.append('user_id', getCurrentUserId()); // Assume this function exists

                // Send POST to Node.js backend
                const response = await fetch('http://localhost:3000/api/registrations/upload-receipt', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${getAuthToken()}`, // Assume this function exists
                    },
                    body: formData
                });

                const result = await response.json();

                if (response.ok) {
                    // Success
                    showAlert('Receipt uploaded successfully! Please wait for verification.', 'success');

                    // Update event payment status in local data
                    const eventIndex = registeredEvents.findIndex(e => e.id == eventId);
                    if (eventIndex !== -1) {
                        registeredEvents[eventIndex].payment_status = 'pending';
                    }

                    // Refresh the events display
                    loadYourEvents();

                    // Close modal
                    bootstrap.Modal.getInstance(document.getElementById('uploadReceiptModal')).hide();

                } else {
                    throw new Error(result.message || 'Upload failed');
                }

            } catch (error) {
                console.error('Upload error:', error);
                showAlert('Failed to upload receipt. Please try again.', 'danger');
            } finally {
                // Hide loading state
                submitBtn.disabled = false;
                spinner.classList.add('d-none');
            }
        }

        // Show QR Code function
        async function showQRCode(eventId) {
            try {
                // Get QR code from Node.js backend
                const response = await fetch(`http://localhost:3000/api/registrations/qr-code/${eventId}`, {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${getAuthToken()}`,
                        'Content-Type': 'application/json'
                    }
                });

                const result = await response.json();

                if (response.ok && result.qr_code) {
                    // Display QR code in modal
                    document.getElementById('qrCodeContainer').innerHTML = `
                        <img src="${result.qr_code}" alt="Event QR Code" class="img-fluid" style="max-width: 250px;">
                    `;

                    const modal = new bootstrap.Modal(document.getElementById('qrCodeModal'));
                    modal.show();
                } else {
                    throw new Error(result.message || 'Failed to load QR code');
                }

            } catch (error) {
                console.error('QR Code error:', error);
                showAlert('Failed to load QR code. Please try again.', 'danger');
            }
        }

        // Check payment status periodically (optional)
        function checkPaymentStatusUpdates() {
            setInterval(async () => {
                try {
                    const response = await fetch('http://localhost:3000/api/registrations/payment-status', {
                        method: 'GET',
                        headers: {
                            'Authorization': `Bearer ${getAuthToken()}`,
                            'Content-Type': 'application/json'
                        }
                    });

                    const result = await response.json();

                    if (response.ok && result.events) {
                        // Update local events data with new payment statuses
                        result.events.forEach(updatedEvent => {
                            const eventIndex = registeredEvents.findIndex(e => e.id == updatedEvent.id);
                            if (eventIndex !== -1) {
                                registeredEvents[eventIndex].payment_status = updatedEvent.payment_status;
                            }
                        });

                        // Refresh display
                        loadYourEvents();
                    }
                } catch (error) {
                    console.error('Status check error:', error);
                }
            }, 30000); // Check every 30 seconds
        }

        // Helper functions (assume these exist in your app)
        function getCurrentUserId() {
            // Return current user ID
            return window.currentUserId || 1;
        }

        function getAuthToken() {
            // Return auth token for API calls
            return localStorage.getItem('token') || '';
        }

        function showAlert(message, type) {
            // Show bootstrap alert or custom notification
            // Implementation depends on your existing alert system
            alert(message); // Simple fallback
        }

        // Initialize periodic status checking when page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Start checking payment status updates
            checkPaymentStatusUpdates();
        });
    </script>
@endpush
