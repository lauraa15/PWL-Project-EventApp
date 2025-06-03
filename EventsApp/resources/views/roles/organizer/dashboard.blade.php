@extends('layouts.app')

@section('title', 'Event Management Dashboard')

@section('styles')
    <style>
        .event-card {
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }
        .event-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .event-status-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
        }
        .event-image {
            height: 180px;
            object-fit: cover;
        }
        .stats-card {
            transition: all 0.3s ease;
        }
        .stats-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .ticket-tier {
            border-left: 4px solid #4e73df;
            padding-left: 10px;
            margin-bottom: 15px;
        }
        .nav-pills .nav-link.active {
            background-color: #4e73df;
        }
    </style>
@endsection

@section('content')
    <div class="page-heading">
        <div class="d-flex justify-content-between align-items-center">
            <h3>Event Management</h3>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createEventModal">
                <i class="bi-plus-circle"></i> Create New Event
            </button>
        </div>
    </div>

    <div class="page-content">
        <!-- Stats Cards -->
        <section class="row">
            <div class="col-6 col-lg-3 col-md-6">
                <div class="card stats-card">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                <div class="stats-icon purple mb-2">
                                    <i class="bi-calendar-event"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Total Events</h6>
                                <h6 class="font-extrabold mb-0">24</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 col-md-6">
                <div class="card stats-card">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                <div class="stats-icon blue mb-2">
                                    <i class="bi-ticket-perforated"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Tickets Sold</h6>
                                <h6 class="font-extrabold mb-0">1,234</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 col-md-6">
                <div class="card stats-card">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                <div class="stats-icon green mb-2">
                                    <i class="bi-currency-dollar"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Total Revenue</h6>
                                <h6 class="font-extrabold mb-0">$24,567</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 col-md-6">
                <div class="card stats-card">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                <div class="stats-icon red mb-2">
                                    <i class="bi-people"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Attendees</h6>
                                <h6 class="font-extrabold mb-0">987</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Event Management Tabs -->
        <section class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-pills card-header-pills">
                            <li class="nav-item">
                                <a class="nav-link active" href="#upcoming-events" data-bs-toggle="pill">Upcoming Events</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#past-events" data-bs-toggle="pill">Past Events</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#draft-events" data-bs-toggle="pill">Drafts</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <!-- Upcoming Events Tab -->
                            <div class="tab-pane fade show active" id="upcoming-events">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Event Name</th>
                                                <th>Date</th>
                                                <th>Location</th>
                                                <th>Tickets</th>
                                                <th>Revenue</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Summer Music Festival</td>
                                                <td>June 15, 2023</td>
                                                <td>Central Park, NY</td>
                                                <td>450/500</td>
                                                <td>$12,345</td>
                                                <td><span class="badge bg-success">Published</span></td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button class="btn btn-sm btn-outline-primary">View</button>
                                                        <button class="btn btn-sm btn-outline-secondary">Edit</button>
                                                        <button class="btn btn-sm btn-outline-danger">Cancel</button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Tech Conference 2023</td>
                                                <td>July 22, 2023</td>
                                                <td>Convention Center, SF</td>
                                                <td>320/400</td>
                                                <td>$9,876</td>
                                                <td><span class="badge bg-success">Published</span></td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button class="btn btn-sm btn-outline-primary">View</button>
                                                        <button class="btn btn-sm btn-outline-secondary">Edit</button>
                                                        <button class="btn btn-sm btn-outline-danger">Cancel</button>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <!-- Past Events Tab -->
                            <div class="tab-pane fade" id="past-events">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Event Name</th>
                                                <th>Date</th>
                                                <th>Location</th>
                                                <th>Attendance</th>
                                                <th>Revenue</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Spring Art Exhibition</td>
                                                <td>May 5, 2023</td>
                                                <td>Art Gallery, LA</td>
                                                <td>280/300</td>
                                                <td>$8,400</td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button class="btn btn-sm btn-outline-primary">View Report</button>
                                                        <button class="btn btn-sm btn-outline-secondary">Edit</button>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <!-- Draft Events Tab -->
                            <div class="tab-pane fade" id="draft-events">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Event Name</th>
                                                <th>Date</th>
                                                <th>Location</th>
                                                <th>Last Updated</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Food & Wine Expo</td>
                                                <td>August 5, 2023</td>
                                                <td>Downtown, Chicago</td>
                                                <td>2 days ago</td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button class="btn btn-sm btn-outline-primary">Preview</button>
                                                        <button class="btn btn-sm btn-outline-secondary">Edit</button>
                                                        <button class="btn btn-sm btn-outline-success">Publish</button>
                                                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                                                    </div>
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

        <!-- Recent Activity -->
        <section class="row mt-4">
            <div class="col-12 col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Recent Activity</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-lg">
                                <thead>
                                    <tr>
                                        <th>Event</th>
                                        <th>Activity</th>
                                        <th>Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="col-3">Summer Music Festival</td>
                                        <td class="col-auto">Ticket purchased by John Doe</td>
                                        <td class="col-2">2 hours ago</td>
                                    </tr>
                                    <tr>
                                        <td class="col-3">Tech Conference 2023</td>
                                        <td class="col-auto">Event details updated</td>
                                        <td class="col-2">5 hours ago</td>
                                    </tr>
                                    <tr>
                                        <td class="col-3">Food & Wine Expo</td>
                                        <td class="col-auto">Event created</td>
                                        <td class="col-2">1 day ago</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Quick Actions</h4>
                    </div>
                    <div class="card-body">
                        <button class="btn btn-outline-primary w-100 mb-3" data-bs-toggle="modal" data-bs-target="#createEventModal">
                            <i class="bi-calendar-plus"></i> Create New Event
                        </button>
                        <button class="btn btn-outline-secondary w-100 mb-3">
                            <i class="bi-ticket-perforated"></i> Manage Ticket Types
                        </button>
                        <button class="btn btn-outline-success w-100 mb-3">
                            <i class="bi-people"></i> View Attendees
                        </button>
                        <button class="btn btn-outline-info w-100 mb-3">
                            <i class="bi-graph-up"></i> Generate Reports
                        </button>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Create Event Modal -->
    <div class="modal fade" id="createEventModal" tabindex="-1" aria-labelledby="createEventModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createEventModalLabel">Create New Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="eventName" class="form-label">Event Name *</label>
                                <input type="text" class="form-control" id="eventName" required>
                            </div>
                            <div class="col-md-6">
                                <label for="eventCategory" class="form-label">Category *</label>
                                <select class="form-select" id="eventCategory" required>
                                    <option value="">Select Category</option>
                                    <option value="music">Music</option>
                                    <option value="sports">Sports</option>
                                    <option value="arts">Arts & Theater</option>
                                    <option value="food">Food & Drink</option>
                                    <option value="tech">Technology</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="startDate" class="form-label">Start Date & Time *</label>
                                <input type="datetime-local" class="form-control" id="startDate" required>
                            </div>
                            <div class="col-md-6">
                                <label for="endDate" class="form-label">End Date & Time *</label>
                                <input type="datetime-local" class="form-control" id="endDate" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="eventLocation" class="form-label">Location *</label>
                            <input type="text" class="form-control" id="eventLocation" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="eventDescription" class="form-label">Description *</label>
                            <textarea class="form-control" id="eventDescription" rows="3" required></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="eventImage" class="form-label">Event Image</label>
                            <input class="form-control" type="file" id="eventImage" accept="image/*">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Ticket Types *</label>
                            <div id="ticketTypesContainer">
                                <div class="ticket-tier mb-3">
                                    <div class="d-flex justify-content-between mb-2">
                                        <h6>General Admission</h6>
                                        <button type="button" class="btn btn-sm btn-outline-danger remove-ticket-type">Remove</button>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label">Ticket Name *</label>
                                            <input type="text" class="form-control" value="General Admission" required>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label">Price ($) *</label>
                                            <input type="number" class="form-control" value="50" min="0" required>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label">Quantity Available *</label>
                                            <input type="number" class="form-control" value="200" min="1" required>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label">Sales End Date</label>
                                            <input type="datetime-local" class="form-control">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Description</label>
                                            <textarea class="form-control" rows="2"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" id="addTicketType" class="btn btn-sm btn-outline-primary">Add Another Ticket Type</button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary">Save as Draft</button>
                        <button type="submit" class="btn btn-success">Publish Event</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
// Ambil token dari localStorage
    const token = localStorage.getItem('token');

    if (!token) {
        // Kalau ga ada token, redirect ke login
        window.location.href = '/login';
    } else {
        try {
            // Decode payload JWT
            const payload = JSON.parse(atob(token.split('.')[1]));
            const roleId = payload.role_id;

            if (roleId != 3) {
                // Bukan admin
                alert("Akses ditolak!");
                window.location.href = '/login';
            }
        } catch (e) {
            console.error('JWT Decode error:', e);
            window.location.href = '/login';
        }
    }
</script>
@endsection