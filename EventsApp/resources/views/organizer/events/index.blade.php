@extends('layouts.organizer')

@section('organizer-content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Events</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('organizer.events.create') }}" class="btn btn-primary">
            <i class="bi bi-plus"></i> Create New Event
        </a>
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
                        <th>Name</th>
                        <th>Date</th>
                        <th>Registration</th>
                        <th>Participants</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $event)
                        <tr>
                            <td>
                                <img src="{{ $event->poster_image ? Storage::url($event->poster_image) : asset('images/default-event.jpg') }}" 
                                     alt="{{ $event->name }}" class="img-thumbnail me-2" style="width: 50px">
                                {{ $event->name }}
                            </td>
                            <td>
                                {{ $event->start_date->format('M d, Y') }}
                                @if($event->end_date->ne($event->start_date))
                                    - {{ $event->end_date->format('M d, Y') }}
                                @endif
                            </td>
                            <td>
                                Opens: {{ $event->registration_open_date->format('M d, Y') }}<br>
                                Closes: {{ $event->registration_close_date->format('M d, Y') }}
                            </td>
                            <td>
                                {{ $event->current_participants }} / {{ $event->max_participants }}
                                <div class="progress" style="height: 5px;">
                                    <div class="progress-bar" role="progressbar" 
                                         style="width: {{ ($event->current_participants / $event->max_participants) * 100 }}%">
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($event->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                        Actions
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('organizer.events.edit', $event) }}">
                                                <i class="bi bi-pencil"></i> Edit Event
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('organizer.events.sessions.index', $event) }}">
                                                <i class="bi bi-calendar2-week"></i> Manage Sessions
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('organizer.events.scan-qr', $event) }}">
                                                <i class="bi bi-qr-code-scan"></i> Scan QR Code
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('organizer.events.certificates', $event) }}">
                                                <i class="bi bi-award"></i> Manage Certificates
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('organizer.events.destroy', $event) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger" 
                                                        onclick="return confirm('Are you sure you want to delete this event?')">
                                                    <i class="bi bi-trash"></i> Delete Event
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No events found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $events->links() }}
        </div>
    </div>
</div>
@endsection
