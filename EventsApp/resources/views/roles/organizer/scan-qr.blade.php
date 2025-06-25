@extends('layouts.app')

@section('content')
<div class="page-heading">
    <h3>Scan Kehadiran Peserta</h3>
</div>
<div class="page-content">
    <div class="card">
        <div class="card-body">
            <div class="mb-3">
                <label for="session_id">Pilih Sesi</label>
                <select id="session_id" class="form-select">
                    <option value="1">Sesi 1</option>
                    <option value="2">Sesi 2</option>
                    <!-- Tambahkan opsi sesuai sesi di database -->
                </select>
            </div>

            <div id="reader" style="width: 300px;"></div>
            <div id="result" class="mt-3 text-success fw-bold"></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- CDN html5-qrcode -->
<!-- <script src="https://unpkg.com/html5-qrcode@2.3.10/html5-qrcode.min.js"></script> -->
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const token = localStorage.getItem('token');
        const qrScanner = new Html5Qrcode("reader");

        function onScanSuccess(decodedText, decodedResult) {
            document.getElementById("result").innerHTML = `✅ QR Code: ${decodedText}`;
            const sessionId = document.getElementById('session_id').value;

            fetch('/organizer/scan-qr', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    qr_code_text: decodedText,
                    session_id: document.getElementById('session_id').value,
                }),
            })
            .then(response => response.json())
            .then(data => {
                // Handle response if needed
                console.log(data);
            })
            .catch(error => {
                console.error('Error:', error);
            });

        }

        Html5Qrcode.getCameras().then((cameras) => {
            if (cameras && cameras.length) {
                qrScanner.start(
                    cameras[0].id,
                    { fps: 10, qrbox: 250 },
                    onScanSuccess
                );
            } else {
                alert("❌ Tidak ada kamera terdeteksi!");
            }
        }).catch((err) => {
            console.error("❌ Gagal akses kamera:", err);
            alert("❌ Gagal akses kamera.");
        });
    });
</script>
@endpush
