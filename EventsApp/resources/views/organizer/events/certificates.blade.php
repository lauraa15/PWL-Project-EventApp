@extends('layouts.organizer')

@section('organizer-content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Manage Certificates - {{ $event->name }}</h1>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="card">
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Bulk Certificate Upload</h5>
                        <form action="{{ route('organizer.events.certificates.bulk-upload', $event) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="certificate_files" class="form-label">Upload PDF Certificates</label>
                                <input type="file" class="form-control" id="certificate_files" name="certificates[]" accept=".pdf" multiple required>
                                <small class="text-muted">You can select multiple PDF files. File names should match participant registration codes.</small>
                            </div>
                            <button type="submit" class="btn btn-primary">Upload Certificates</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Single Certificate Upload</h5>
                        <form action="{{ route('organizer.events.certificates.upload', $event) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="registration_id" class="form-label">Select Participant</label>
                                <select class="form-select" id="registration_id" name="registration_id" required>
                                    <option value="">Choose participant...</option>
                                    @foreach($registrations as $registration)
                                        <option value="{{ $registration->id }}">
                                            {{ $registration->user->name }} - {{ $registration->registration_code }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="certificate_file" class="form-label">Upload Certificate PDF</label>
                                <input type="file" class="form-control" id="certificate_file" name="certificate_file" accept=".pdf" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Upload Certificate</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <h5>Uploaded Certificates</h5>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Participant</th>
                        <th>Registration Code</th>
                        <th>Certificate Code</th>
                        <th>Upload Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($certificates as $certificate)
                        <tr>
                            <td>{{ $certificate->registration->user->name }}</td>
                            <td>{{ $certificate->registration->registration_code }}</td>
                            <td>{{ $certificate->certificate_code }}</td>
                            <td>{{ $certificate->created_at->format('M d, Y H:i') }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ Storage::url($certificate->certificate_file) }}" 
                                       class="btn btn-sm btn-outline-primary" target="_blank">
                                        <i class="bi bi-file-pdf"></i> View
                                    </a>
                                    <form action="{{ route('organizer.certificates.destroy', $certificate) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                onclick="return confirm('Are you sure you want to delete this certificate?')">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No certificates uploaded yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $certificates->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Select2 for better select box UI
        if (typeof $.fn.select2 !== 'undefined') {
            $('#registration_id').select2({
                placeholder: 'Choose participant...',
                width: '100%'
            });
        }
    });
</script>
@endpush
@endsection
