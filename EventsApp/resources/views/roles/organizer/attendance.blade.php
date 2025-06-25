@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Riwayat Kehadiran & Upload Sertifikat</h3>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Sesi</th>
                <th>Waktu Scan</th>
                <th>Sertifikat</th>
            </tr>
        </thead>
        <tbody id="attendance-table-body">
            <tr><td colspan="4">Memuat data...</td></tr>
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const userId = 6;

    fetch(`http://localhost:3000/api/attendance/user/${userId}`)
        .then(res => res.json())
        .then(data => {
            const tableBody = document.getElementById('attendance-table-body');
            tableBody.innerHTML = '';

            if (data.success && data.data.length > 0) {
                data.data.forEach(item => {
                    const certificateCell = item.certificate_file_path
                        ? `<a href="${item.certificate_file_path}" target="_blank">Lihat</a>`
                        : `
                            <form onsubmit="uploadCertificate(event, '${item.id}')">
                                <input type="file" name="certificate" accept="application/pdf" required>
                                <button type="submit" class="btn btn-sm btn-primary mt-1">Upload</button>
                            </form>
                        `;

                    const row = `
                        <tr>
                            <td>${item.name}</td>
                            <td>${item.session_name}</td>
                            <td>${new Date(item.scan_time).toLocaleString()}</td>
                            <td>${certificateCell}</td>
                        </tr>
                    `;
                    tableBody.innerHTML += row;
                });
            } else {
                tableBody.innerHTML = `<tr><td colspan="4">Tidak ada data kehadiran.</td></tr>`;
            }
        })
        .catch(err => {
            console.error('❌ Gagal fetch:', err);
            document.getElementById('attendance-table-body').innerHTML = `<tr><td colspan="4">Gagal memuat data.</td></tr>`;
        });
});

// Fungsi Upload Sertifikat
function uploadCertificate(event, attendanceId) {
    event.preventDefault();
    const form = event.target;
    const fileInput = form.querySelector('input[name="certificate"]');
    const file = fileInput.files[0];

    if (!file) {
        alert('Pilih file terlebih dahulu.');
        return;
    }

    const formData = new FormData();
    formData.append('certificate', file);

    fetch(`http://localhost:3000/api/attendance/upload/${attendanceId}`, {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(response => {
        if (response.success) {
            alert('✅ Sertifikat berhasil diunggah!');
            location.reload();
        } else {
            alert('❌ Gagal unggah sertifikat: ' + response.message);
        }
    })
    .catch(err => {
        console.error('❌ Upload error:', err);
        alert('❌ Terjadi kesalahan saat mengunggah.');
    });
}
</script>
@endpush
