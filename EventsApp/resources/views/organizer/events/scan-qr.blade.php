@extends('layouts.organizer')

@section('organizer-content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Scan QR Code - {{ $event->name }}</h1>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Camera Preview</h5>
                <div id="reader"></div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Recent Scans</h5>
                <div id="recent-scans">
                    <div class="list-group" id="scan-history">
                        <!-- Scan history will be populated here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    function onScanSuccess(qrCodeMessage) {
        // Send the QR code to the server
        fetch('{{ route('organizer.events.scan-qr', $event) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                qr_code: qrCodeMessage
            })
        })
        .then(response => response.json())
        .then(data => {
            // Add the scan to history
            const scanHistory = document.getElementById('scan-history');
            const scanItem = document.createElement('div');
            scanItem.className = 'list-group-item';
            scanItem.innerHTML = `
                <div class="d-flex w-100 justify-content-between">
                    <h6 class="mb-1">${data.attendance.participant_name}</h6>
                    <small>${new Date().toLocaleTimeString()}</small>
                </div>
                <p class="mb-1">Session: ${data.attendance.session_name || 'Full Event'}</p>
                <small class="text-success">Attendance recorded successfully</small>
            `;
            scanHistory.prepend(scanItem);
            
            // Show success notification
            alert('Attendance recorded successfully!');
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error recording attendance. Please try again.');
        });
    }

    function onScanError(errorMessage) {
        // Handle scan error
        console.warn(`QR Code scan error: ${errorMessage}`);
    }

    document.addEventListener('DOMContentLoaded', function() {
        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", 
            { 
                fps: 10,
                qrbox: 250
            }
        );
        html5QrcodeScanner.render(onScanSuccess, onScanError);
    });
</script>
@endpush
@endsection
