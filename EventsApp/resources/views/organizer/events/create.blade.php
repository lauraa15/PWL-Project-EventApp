@extends('layouts.organizer')

@section('organizer-content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Create New Event</h1>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('organizer.events.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="event_type_id" class="form-label">Event Type</label>
                    <select class="form-select @error('event_type_id') is-invalid @enderror" id="event_type_id" name="event_type_id" required>
                        <option value="">Select Event Type</option>
                        @foreach($eventTypes as $type)
                            <option value="{{ $type->id }}" {{ old('event_type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('event_type_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="name" class="form-label">Event Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                          id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                           id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                    @error('start_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                           id="end_date" name="end_date" value="{{ old('end_date') }}" required>
                    @error('end_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="location" class="form-label">Location</label>
                <input type="text" class="form-control @error('location') is-invalid @enderror" 
                       id="location" name="location" value="{{ old('location') }}" required>
                @error('location')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="poster_image" class="form-label">Event Poster</label>
                <input type="file" class="form-control @error('poster_image') is-invalid @enderror" 
                       id="poster_image" name="poster_image" accept="image/*">
                @error('poster_image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="registration_fee" class="form-label">Registration Fee</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" class="form-control @error('registration_fee') is-invalid @enderror" 
                               id="registration_fee" name="registration_fee" value="{{ old('registration_fee', 0) }}" min="0" required>
                    </div>
                    @error('registration_fee')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="registration_type" class="form-label">Registration Type</label>
                    <select class="form-select @error('registration_type') is-invalid @enderror" 
                            id="registration_type" name="registration_type" required>
                        <option value="event_only" {{ old('registration_type') == 'event_only' ? 'selected' : '' }}>Event Only</option>
                        <option value="session_only" {{ old('registration_type') == 'session_only' ? 'selected' : '' }}>Session Only</option>
                        <option value="both" {{ old('registration_type') == 'both' ? 'selected' : '' }}>Both</option>
                    </select>
                    @error('registration_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="max_participants" class="form-label">Maximum Participants</label>
                    <input type="number" class="form-control @error('max_participants') is-invalid @enderror" 
                           id="max_participants" name="max_participants" value="{{ old('max_participants') }}" min="1" required>
                    @error('max_participants')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="registration_open_date" class="form-label">Registration Opens</label>
                    <input type="date" class="form-control @error('registration_open_date') is-invalid @enderror" 
                           id="registration_open_date" name="registration_open_date" value="{{ old('registration_open_date') }}" required>
                    @error('registration_open_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="registration_close_date" class="form-label">Registration Closes</label>
                    <input type="date" class="form-control @error('registration_close_date') is-invalid @enderror" 
                           id="registration_close_date" name="registration_close_date" value="{{ old('registration_close_date') }}" required>
                    @error('registration_close_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="certificate_type" class="form-label">Certificate Type</label>
                <select class="form-select @error('certificate_type') is-invalid @enderror" 
                        id="certificate_type" name="certificate_type" required>
                    <option value="per_event" {{ old('certificate_type') == 'per_event' ? 'selected' : '' }}>Per Event</option>
                    <option value="per_session" {{ old('certificate_type') == 'per_session' ? 'selected' : '' }}>Per Session</option>
                </select>
                @error('certificate_type')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div id="sessions-container" class="d-none">
                <h3 class="h4 mt-4 mb-3">Event Sessions</h3>
                <div class="sessions"></div>
                <button type="button" class="btn btn-outline-primary mt-2" id="add-session">
                    <i class="bi bi-plus"></i> Add Session
                </button>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Create Event</button>
                <a href="{{ route('organizer.events.index') }}" class="btn btn-link">Cancel</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const sessionTemplate = `
        <div class="session-item card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0">Session</h5>
                    <button type="button" class="btn btn-outline-danger btn-sm remove-session">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label">Session Name</label>
                        <input type="text" class="form-control" name="sessions[][name]" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Start Time</label>
                        <input type="datetime-local" class="form-control" name="sessions[][start_time]" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">End Time</label>
                        <input type="datetime-local" class="form-control" name="sessions[][end_time]" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Location</label>
                        <input type="text" class="form-control" name="sessions[][location]" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Speaker</label>
                        <input type="text" class="form-control" name="sessions[][speaker]">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Maximum Participants</label>
                        <input type="number" class="form-control" name="sessions[][max_participants]" min="1">
                    </div>
                </div>
            </div>
        </div>
    `;

    document.addEventListener('DOMContentLoaded', function() {
        const sessionsContainer = document.getElementById('sessions-container');
        const sessionsDiv = sessionsContainer.querySelector('.sessions');
        const addSessionBtn = document.getElementById('add-session');
        const registrationTypeSelect = document.getElementById('registration_type');

        // Show/hide sessions based on registration type
        registrationTypeSelect.addEventListener('change', function() {
            if (this.value === 'session_only' || this.value === 'both') {
                sessionsContainer.classList.remove('d-none');
            } else {
                sessionsContainer.classList.add('d-none');
            }
        });

        // Add new session
        addSessionBtn.addEventListener('click', function() {
            sessionsDiv.insertAdjacentHTML('beforeend', sessionTemplate);
        });

        // Remove session
        sessionsDiv.addEventListener('click', function(e) {
            if (e.target.closest('.remove-session')) {
                e.target.closest('.session-item').remove();
            }
        });
    });
</script>
@endpush
@endsection
