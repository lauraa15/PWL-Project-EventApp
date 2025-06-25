@extends('layouts.app')

@section('title', 'Event Management Dashboard')

@push('styles')
    <style>
        .event-card {
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }

        .event-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
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
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
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
@endpush

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
                        @if(count($events) === 0)
                            <div class="alert alert-info text-center">
                                Anda belum menambahkan Event.
                            </div>
                        @else
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
                                        <tbody >
                                            @foreach ($events as $event)
                                                @if (strtotime($event['start_date']) > time() )
                                                    <tr>
                                                        <td>{{ $event['name'] }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($event['start_date'])->format('F j, Y') }}
                                                        </td>
                                                        <td>{{ $event['location'] }}</td>
                                                        <td>{{ $event['current_participants'] ?? 0 }}/{{ $event['max_participants'] ?? 0 }}
                                                        </td>
                                                        <td>Rp{{ number_format($event['revenue'] ?? 0, 0, ',', '.') }}</td>
                                                        @if($event['is_active'] === 1)
                                                            <td><span class="badge bg-success">Active</span></td>
                                                        @endif
                                                        <td>
                                                            <div class="btn-group">
                                                                <button 
                                                                    class="btn btn-sm btn-outline-primary"
                                                                    onclick="showEventDetails({{ $event['id'] }})">
                                                                    View
                                                                </button>
                                                                <button 
                                                                    class="btn btn-sm btn-outline-secondary btn-edit-event"
                                                                    data-id="{{ $event['id'] }}"
                                                                    data-name="{{ $event['name'] }}"
                                                                    data-event-type-id="{{ $event['event_type_id'] }}"
                                                                    data-description="{{ $event['description'] }}"
                                                                    data-start-date="{{ $event['start_date'] }}"
                                                                    data-end-date="{{ $event['end_date'] }}"
                                                                    data-location="{{ $event['location'] }}"
                                                                    data-registration-open-date="{{ $event['registration_open_date'] }}"
                                                                    data-registration-close-date="{{ $event['registration_close_date'] }}"
                                                                    data-registration-fee="{{ $event['registration_fee'] }}"
                                                                    data-registration-type="{{ $event['registration_type'] }}"
                                                                    data-max-participants="{{ $event['max_participants'] }}"
                                                                    data-certificate-type="{{ $event['certificate_type'] }}"
                                                                    data-is-active="{{ $event['is_active'] }}"
                                                                >
                                                                    Edit
                                                                </button>
                                                                <button
                                                                    class="btn btn-sm btn-outline-danger">Cancel</button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
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
                                            @foreach ($events as $event)
                                                @if (strtotime($event['end_date']) < time())
                                                    <tr>
                                                        <td>{{ $event['name'] }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($event['end_date'])->format('F j, Y') }}
                                                        </td>
                                                        <td>{{ $event['location'] }}</td>
                                                        <td>{{ $event['current_participants'] ?? 0 }}/{{ $event['max_participants'] ?? 0 }}
                                                        </td>
                                                        <td>Rp{{ number_format($event['revenue'] ?? 0, 0, ',', '.') }}</td>
                                                        <td>
                                                            <div class="btn-group">
                                                                <button class="btn btn-sm btn-outline-primary">View
                                                                    Report</button>
                                                                <button
                                                                    class="btn btn-sm btn-outline-secondary">Edit</button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
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
                                            @foreach ($events as $event)
                                                @if ($event['is_active'] === 0)
                                                    <tr>
                                                        <td>{{ $event['name'] }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($event['start_date'])->format('F j, Y') }}
                                                        </td>
                                                        <td>{{ $event['location'] }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($event['updated_at'])->diffForHumans() }}
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-secondary">Non-Active</span>
                                                        </td>
                                                        <td>
                                                            <div class="btn-group">
                                                                <button
                                                                    class="btn btn-sm btn-outline-primary">Preview</button>
                                                                <button
                                                                    class="btn btn-sm btn-outline-secondary">Edit</button>
                                                                <button
                                                                    class="btn btn-sm btn-outline-success">Publish</button>
                                                                <button
                                                                    class="btn btn-sm btn-outline-danger">Delete</button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @endif
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
                        <button class="btn btn-outline-primary w-100 mb-3" data-bs-toggle="modal"
                            data-bs-target="#createEventModal">
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
    <div class="modal fade" id="createEventModal" tabindex="-1" aria-labelledby="createEventModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="createEventForm" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="createEventModalLabel">Create New Event</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Event Name *</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                            <div class="col-md-6">
                                <label for="event_type_id" class="form-label">Category *</label>
                                <select class="form-select" name="event_type_id" id="eventCategory" required>
                                    <option value="">Select Category</option>
                                    @foreach ($eventTypes as $type)
                                        <option value="{{ $type['id'] }}">{{ $type['type'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="start_date" class="form-label">Start Date & Time *</label>
                                <input type="datetime-local" class="form-control" name="start_date" required>
                            </div>
                            <div class="col-md-6">
                                <label for="end_date" class="form-label">End Date & Time *</label>
                                <input type="datetime-local" class="form-control" name="end_date" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="location" class="form-label">Location *</label>
                            <input type="text" class="form-control" name="location" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description *</label>
                            <textarea class="form-control" name="description" rows="3" required></textarea>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="registration_open_date" class="form-label">Registration Opens *</label>
                                <input type="datetime-local" class="form-control" name="registration_open_date" required>
                            </div>
                            <div class="col-md-6">
                                <label for="registration_close_date" class="form-label">Registration Closes *</label>
                                <input type="datetime-local" class="form-control" name="registration_close_date"
                                    required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="registration_fee" class="form-label">Registration Fee (Rp) *</label>
                                <input type="number" class="form-control" name="registration_fee" min="0"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label for="registration_type" class="form-label">Registration Type *</label>
                                <select class="form-select" name="registration_type" required>
                                    <option value="event_only">Event Only</option>
                                    <option value="session_only">Session Only</option>
                                    <option value="both">Both</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="max_participants" class="form-label">Max Participants *</label>
                                <input type="number" class="form-control" name="max_participants" min="1"
                                    required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="certificate_type" class="form-label">Certificate Type *</label>
                                <select class="form-select" name="certificate_type" required>
                                    <option value="per_event">Per Event</option>
                                    <option value="per_session">Per Session</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="poster_image" class="form-label">Poster Image</label>
                                <input type="file" class="form-control" name="poster_image" accept="image/*">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Active Status</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="is_active" value="1"
                                        checked>
                                    <label class="form-check-label">Active</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="is_active" value="0">
                                    <label class="form-check-label">Inactive</label>
                                </div>
                            </div>
                        </div>
                    </div> <!-- modal-body -->

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Publish Event</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Detail Event -->
<div class="modal fade" id="eventDetailModal" tabindex="-1" aria-labelledby="eventDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <h4 id="detailName"></h4>
                <p id="detailDescription" class="text-muted"></p>

                <ul class="list-group mb-3">
                    <li class="list-group-item"><strong>Kategori:</strong> <span id="detailType"></span></li>
                    <li class="list-group-item"><strong>Lokasi:</strong> <span id="detailLocation"></span></li>
                    <li class="list-group-item"><strong>Tanggal:</strong> <span id="detailDate"></span></li>
                    <li class="list-group-item"><strong>Biaya:</strong> <span id="detailFee"></span></li>
                    <li class="list-group-item"><strong>Sertifikat:</strong> <span id="detailCertificate"></span></li>
                </ul>

                <h5>Daftar Panitia</h5>
                <ul class="list-group" id="committeeList"></ul>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
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
    <script>
    let isEdit = false;
    let currentEventId = null;

    document.querySelectorAll('.btn-edit-event').forEach(button => {
        button.addEventListener('click', function () {
            isEdit = true;
            currentEventId = this.dataset.id;

            const form = document.getElementById('createEventForm');

            // Isi data ke dalam form
            form.name.value = this.dataset.name;
            form.event_type_id.value = this.dataset['eventTypeId'];
            form.description.value = this.dataset.description;
            form.start_date.value = formatDateForInput(this.dataset.startDate);
            form.end_date.value = formatDateForInput(this.dataset.endDate);
            form.location.value = this.dataset.location;
            form.registration_open_date.value = formatDateForInput(this.dataset.registrationOpenDate);
            form.registration_close_date.value = formatDateForInput(this.dataset.registrationCloseDate);
            form.registration_fee.value = this.dataset.registrationFee;
            form.registration_type.value = this.dataset.registrationType;
            form.max_participants.value = this.dataset.maxParticipants;
            form.certificate_type.value = this.dataset.certificateType;

            const activeRadios = form.querySelectorAll('input[name="is_active"]');
            activeRadios.forEach(r => {
                r.checked = parseInt(r.value) === parseInt(this.dataset.isActive);
            });

            // Ganti teks tombol
            document.getElementById('createEventModalLabel').innerText = 'Edit Event';
            form.querySelector('button[type="submit"]').innerText = 'Update Event';

            // Tampilkan modal
            const modal = new bootstrap.Modal(document.getElementById('createEventModal'));
            modal.show();
        });
    });
    function formatDateForInput(dateString) {
    const date = new Date(dateString);
    const offset = date.getTimezoneOffset();
    const localDate = new Date(date.getTime() - offset * 60 * 1000);
    return localDate.toISOString().slice(0, 16); // yyyy-MM-ddTHH:mm
}

</script>
    <script>
        document.getElementById('createEventForm').addEventListener('submit', async function (e) {
            e.preventDefault();
            const form = e.target;

            const data = {
                name: form.name.value,
                event_type_id: form.event_type_id.value,
                description: form.description.value,
                start_date: form.start_date.value,
                end_date: form.end_date.value,
                location: form.location.value,
                registration_open_date: form.registration_open_date.value,
                registration_close_date: form.registration_close_date.value,
                registration_fee: parseFloat(form.registration_fee.value),
                registration_type: form.registration_type.value,
                max_participants: parseInt(form.max_participants.value),
                current_participants: 0,
                certificate_type: form.certificate_type.value,
                is_active: parseInt(form.is_active.value)
            };

            const url = isEdit
                ? `http://localhost:3000/api/events/update-event/${currentEventId}`
                : `http://localhost:3000/api/events/add-event`;

            const method = isEdit ? 'PUT' : 'POST';

            try {
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data),
                    mode: 'cors'
                });

                const result = await response.json();

                if (response.ok) {
                    Swal.fire({
                        icon: 'success',
                        title: isEdit ? 'Event berhasil diperbarui.' : 'Event berhasil dibuat.',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        form.reset();
                        isEdit = false;
                        currentEventId = null;
                        document.getElementById('createEventModalLabel').innerText = 'Create New Event';
                        form.querySelector('button[type="submit"]').innerText = 'Publish Event';
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: result.message || 'Gagal memproses event.'
                    });
                }
            } catch (err) {
                console.error('Event Error:', err);
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan!',
                    text: 'Gagal menghubungi server.'
                });
            }
        });
    </script>
    <script>
    document.getElementById('createEventModal').addEventListener('hidden.bs.modal', function () {
        const form = document.getElementById('createEventForm');
        form.reset();
        isEdit = false;
        currentEventId = null;
        document.getElementById('createEventModalLabel').innerText = 'Create New Event';
        form.querySelector('button[type="submit"]').innerText = 'Publish Event';
    });
</script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const detailModal = new bootstrap.Modal(document.getElementById('eventDetailModal'));

    window.showEventDetails = async (eventId) => {
        try {
            const token = localStorage.getItem('token');
            const response = await fetch(`http://localhost:3000/api/events/${eventId}`, {
                headers: { Authorization: `Bearer ${token}` }
            });

            const result = await response.json();
            if (!result.data) throw new Error('Gagal mengambil detail event.');

            const event = result.data;

            // Isi data ke modal
            document.getElementById('detailName').textContent = event.name;
            document.getElementById('detailDescription').textContent = event.description || '-';
            document.getElementById('detailType').textContent = event.event_type_name || '-';
            document.getElementById('detailLocation').textContent = event.location || '-';
            document.getElementById('detailDate').textContent =
                `${new Date(event.start_date).toLocaleString()} - ${new Date(event.end_date).toLocaleString()}`;
            document.getElementById('detailFee').textContent = `Rp ${parseInt(event.registration_fee).toLocaleString()}`;
            document.getElementById('detailCertificate').textContent = event.certificate_type;

            // Daftar panitia
            const committeeList = document.getElementById('committeeList');
            committeeList.innerHTML = ''; // kosongkan dulu
            if (event.committees && event.committees.length > 0) {
                event.committees.forEach(panitia => {
                    const li = document.createElement('li');
                    li.classList.add('list-group-item');
                    li.textContent = `${panitia.name} (${panitia.email})`;
                    committeeList.appendChild(li);
                });
            } else {
                committeeList.innerHTML = `<li class="list-group-item text-muted">Tidak ada panitia terdaftar.</li>`;
            }

            detailModal.show();
        } catch (err) {
            console.error('Gagal ambil detail event:', err);
            alert('Gagal menampilkan detail event.');
        }
    };
});
</script>


@endpush
