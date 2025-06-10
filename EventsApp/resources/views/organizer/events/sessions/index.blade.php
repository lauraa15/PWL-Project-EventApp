@extends('layouts.organizer')

@section('organizer-content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Event Sessions - {{ $event->name }}</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSessionModal">
            <i class="bi bi-plus"></i> Add Session
        </button>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Session Name</th>
                        <th>Schedule</th>
                        <th>Location</th>
                        <th>Speaker</th>
                        <th>Participants</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sessions as $session)
                        <tr>
                            <td>
                                {{ $session->name }}
                                @if($session->description)
                                    <br>
                                    <small class="text-muted">{{ $session->description }}</small>
                                @endif
                            </td>
                            <td>
                                {{ $session->start_time->format('M d, Y H:i') }} -
                                {{ $session->end_time->format('H:i') }}
                            </td>
                            <td>{{ $session->location }}</td>
                            <td>{{ $session->speaker }}</td>
                            <td>
                                @if($session->max_participants)
                                    {{ $session->attendee_count }} / {{ $session->max_participants }}
                                    <div class="progress" style="height: 5px;">
                                        <div class="progress-bar" role="progressbar" 
                                            style="width: {{ ($session->attendee_count / $session->max_participants) * 100 }}%">
                                        </div>
                                    </div>
                                @else
                                    {{ $session->attendee_count }} attendees
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-outline-primary edit-session" 
                                            data-session="{{ $session->id }}"
                                            data-bs-toggle="modal" data-bs-target="#editSessionModal">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form action="{{ route('organizer.events.sessions.destroy', [$event, $session]) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                onclick="return confirm('Are you sure you want to delete this session?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No sessions created yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Session Modal -->
<div class="modal fade" id="addSessionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Session</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addSessionForm" action="{{ route('organizer.events.sessions.store', $event) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Session Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="start_time" class="form-label">Start Time</label>
                            <input type="datetime-local" class="form-control" id="start_time" name="start_time" required>
                        </div>
                        <div class="col-md-6">
                            <label for="end_time" class="form-label">End Time</label>
                            <input type="datetime-local" class="form-control" id="end_time" name="end_time" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" class="form-control" id="location" name="location" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="speaker" class="form-label">Speaker</label>
                        <input type="text" class="form-control" id="speaker" name="speaker">
                    </div>
                    
                    <div class="mb-3">
                        <label for="max_participants" class="form-label">Maximum Participants</label>
                        <input type="number" class="form-control" id="max_participants" name="max_participants" min="1">
                        <small class="text-muted">Leave empty for unlimited participants</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Session</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Session Modal -->
<div class="modal fade" id="editSessionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Session</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editSessionForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <!-- Same form fields as Add Session Modal -->
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Session Name</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Description</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_start_time" class="form-label">Start Time</label>
                            <input type="datetime-local" class="form-control" id="edit_start_time" name="start_time" required>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_end_time" class="form-label">End Time</label>
                            <input type="datetime-local" class="form-control" id="edit_end_time" name="end_time" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_location" class="form-label">Location</label>
                        <input type="text" class="form-control" id="edit_location" name="location" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_speaker" class="form-label">Speaker</label>
                        <input type="text" class="form-control" id="edit_speaker" name="speaker">
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_max_participants" class="form-label">Maximum Participants</label>
                        <input type="number" class="form-control" id="edit_max_participants" name="max_participants" min="1">
                        <small class="text-muted">Leave empty for unlimited participants</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Session</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle session edit button click
    document.querySelectorAll('.edit-session').forEach(button => {
        button.addEventListener('click', function() {
            const sessionId = this.dataset.session;
            const session = @json($sessions->keyBy('id'));
            const currentSession = session[sessionId];
            
            // Update form action URL
            const form = document.getElementById('editSessionForm');
            form.action = `{{ route('organizer.events.sessions.index', $event) }}/${sessionId}`;
            
            // Populate form fields
            document.getElementById('edit_name').value = currentSession.name;
            document.getElementById('edit_description').value = currentSession.description;
            document.getElementById('edit_start_time').value = currentSession.start_time.slice(0, 16);
            document.getElementById('edit_end_time').value = currentSession.end_time.slice(0, 16);
            document.getElementById('edit_location').value = currentSession.location;
            document.getElementById('edit_speaker').value = currentSession.speaker;
            document.getElementById('edit_max_participants').value = currentSession.max_participants;
        });
    });

    // Form submission handling with AJAX
    ['addSessionForm', 'editSessionForm'].forEach(formId => {
        const form = document.getElementById(formId);
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            fetch(this.action, {
                method: this.method,
                body: new FormData(this),
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    // Close modal and refresh page
                    bootstrap.Modal.getInstance(this.closest('.modal')).hide();
                    window.location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        });
    });
});
</script>
@endpush
@endsection
