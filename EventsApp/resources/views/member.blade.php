@extends('layouts.app')

@section('title', 'Member Dashboard')

@section('styles')
    <style>
        .event-card {
            transition: all 0.3s ease;
            margin-bottom: 20px;
            cursor: pointer;
        }
        .event-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .event-image {
            height: 180px;
            object-fit: cover;
        }
        .ticket-card {
            border-left: 4px solid #4e73df;
        }
        .badge-category {
            position: absolute;
            top: 10px;
            left: 10px;
            background: rgba(0,0,0,0.7);
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
        }
        .nav-pills .nav-link.active {
            background-color: #4e73df;
        }
    </style>
@endsection

@section('content')
    <div class="page-heading">
        {{-- <h3>Welcome Back, {{ Auth::user()->name }}!</h3> --}}
        <h3>Welcome Back, Member!</h3>
    </div>

    <div class="page-content">
        <!-- Upcoming Events Section -->
        <section class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Upcoming Events You Might Like</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach([1,2,3,4] as $event)
                            <div class="col-md-6 col-lg-3">
                                <div class="card event-card">
                                    <div class="position-relative">
                                        <img src=" class="card-img-top event-image" alt="Event">
                                        <span class="badge-category">Music</span>
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">Summer Concert </h5>
                                        <p class="card-text text-muted">
                                            <i class="bi bi-calendar-event"></i> June , 2023<br>
                                            <i class="bi bi-geo-alt"></i> Central Park, NY
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="fw-bold text-primary">From </span>
                                            <a href="#" class="btn btn-sm btn-primary">View Details</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="text-center mt-3">
                            <a href="#" class="btn btn-outline-primary">Browse All Events</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Member Tickets Section -->
        <section class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-pills card-header-pills">
                            <li class="nav-item">
                                <a class="nav-link active" href="#upcoming-tickets" data-bs-toggle="pill">Upcoming Tickets</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#past-tickets" data-bs-toggle="pill">Past Events</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <!-- Upcoming Tickets Tab -->
                            <div class="tab-pane fade show active" id="upcoming-tickets">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Event</th>
                                                <th>Date</th>
                                                <th>Location</th>
                                                <th>Tickets</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Summer Music Festival</td>
                                                <td>June 15, 2023</td>
                                                <td>Central Park, NY</td>
                                                <td>2 x General Admission</td>
                                                <td><span class="badge bg-success">Confirmed</span></td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button class="btn btn-sm btn-outline-primary">View Tickets</button>
                                                        <button class="btn btn-sm btn-outline-secondary">Transfer</button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Tech Conference 2023</td>
                                                <td>July 22, 2023</td>
                                                <td>Convention Center, SF</td>
                                                <td>1 x VIP Pass</td>
                                                <td><span class="badge bg-success">Confirmed</span></td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button class="btn btn-sm btn-outline-primary">View Tickets</button>
                                                        <button class="btn btn-sm btn-outline-secondary">Transfer</button>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <!-- Past Tickets Tab -->
                            <div class="tab-pane fade" id="past-tickets">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Event</th>
                                                <th>Date</th>
                                                <th>Location</th>
                                                <th>Tickets</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Spring Art Exhibition</td>
                                                <td>May 5, 2023</td>
                                                <td>Art Gallery, LA</td>
                                                <td>1 x General Admission</td>
                                                <td><span class="badge bg-secondary">Attended</span></td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary">View Details</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Recommended Events -->
        <section class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Recommended For You</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach([1,2,3,4] as $event)
                            <div class="col-md-6 col-lg-3">
                                <div class="card event-card">
                                    <div class="position-relative">
                                        <img src="https://source.unsplash.com/random/300x200?concert={{ $event }}" class="card-img-top event-image" alt="Event">
                                        <span class="badge-category">Music</span>
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">Live Band Night {{ $event }}</h5>
                                        <p class="card-text text-muted">
                                            <i class="bi bi-calendar-event"></i> July {{ 5 + $event }}, 2023<br>
                                            <i class="bi bi-geo-alt"></i> Music Hall, NY
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="fw-bold text-primary">${{ 30 + ($event * 5) }}</span>
                                            <a href="#" class="btn btn-sm btn-primary">Buy Tickets</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Ticket Modal (would be shown when clicking "View Tickets") -->
    <div class="modal fade" id="ticketModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Your Ticket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card ticket-card">
                        <div class="card-body">
                            <div class="text-center mb-4">
                                <h4>Summer Music Festival</h4>
                                <p class="text-muted">June 15, 2023 • Central Park, NY</p>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <div>
                                    <h6>Ticket Holder</h6>
                                    <p>John Doe</p>
                                </div>
                                <div>
                                    <h6>Ticket Type</h6>
                                    <p>General Admission</p>
                                </div>
                            </div>
                            <div class="text-center">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=TICKET-12345" alt="QR Code" class="img-fluid mb-3">
                                <p class="text-muted">Ticket #: TICKET-12345</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Download Ticket</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Sample event listener for viewing tickets
            document.querySelectorAll('.btn-outline-primary').forEach(btn => {
                btn.addEventListener('click', function() {
                    if (this.textContent.trim() === 'View Tickets') {
                        const ticketModal = new bootstrap.Modal(document.getElementById('ticketModal'));
                        ticketModal.show();
                    }
                });
            });
        });
    </script>
@endsectionw