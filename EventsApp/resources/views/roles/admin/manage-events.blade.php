@extends('layouts.app')

@section('title', 'Manajemen Event')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" />
@endpush

@section('content')
<section class="section">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Daftar Event</h4>
            <table class="table table-striped" id="event-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Event</th>
                        <th>Jenis</th>
                        <th>Lokasi</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="events-body">
                    <!-- Diisi melalui JavaScript -->
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal fade" id="eventDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Detail Event</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" id="eventDetailContent">
            <!-- Detail akan diisi lewat JS -->
        </div>
        </div>
    </div>
    </div>
</section>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" defer></script>

<script>
document.addEventListener('DOMContentLoaded', async () => {
    const tbody = document.getElementById('events-body">');
    const tableElement = document.querySelector('#event-table');
    const eventDetailModal = new bootstrap.Modal(document.getElementById('eventDetailModal'));


    let events = [];

    try {
        const token = localStorage.getItem('token');
        const response = await fetch('http://localhost:3000/api/events', {
            headers: {
                Authorization: `Bearer ${token}`
            }
        });

        const result = await response.json();
        console.log("Hasil fetch events:", result);

        if (result.success && Array.isArray(result.data)) {
        renderTable(result.data);
        }

        renderTable(result.data); // ← pastikan field sesuai dengan hasil response
        new simpleDatatables.DataTable(tableElement);
    } catch (err) {
        console.error('Gagal fetch events:', err);
        alert('Terjadi kesalahan saat mengambil data event.');
    }

    function renderTable(events) {
        const tbody = document.getElementById('events-body');
        tbody.innerHTML = '';
        events.forEach((event, index) => {
            const row = `
            <tr>
                <td>${index + 1}</td>
                <td>
                    <a href="#" onclick="showEventDetails(${event.id})" class="text-decoration-none">
                        ${event.name}
                    </a>
                </td>
                <td>${event.event_type_name}</td>
                <td>${event.location}</td>
                <td>${event.start_date}</td>
                <td>${event.end_date}</td>
                <td>${event.is_active ? 'Aktif' : 'Nonaktif'}</td>
                <td>
                <button class="btn btn-sm ${event.is_active ? 'btn-danger' : 'btn-success'}" onclick="toggleEventStatus(${event.id})">
                    ${event.is_active ? 'Nonaktifkan' : 'Aktifkan'}
                    </button>
                </td>
            </tr>
            `;
            tbody.innerHTML += row;
        });
        }

    window.toggleEventStatus = async (eventId) => {
    const token = localStorage.getItem('token');

    const confirmResult = await Swal.fire({
        title: 'Yakin?',
        text: 'Kamu akan mengubah status event ini.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, ubah!',
        cancelButtonText: 'Batal'
    });

    if (!confirmResult.isConfirmed) return;

    try {
        const response = await fetch(`http://localhost:3000/api/events/${eventId}/toggle`, {
        method: 'PATCH',
        headers: {
            Authorization: `Bearer ${token}`
        }
        });

        const result = await response.json();
        if (response.ok) {
        Swal.fire('Berhasil!', result.message, 'success');
        location.reload();
        } else {
        Swal.fire('Gagal!', result.message, 'error');
        }
    } catch (err) {
        console.error('Toggle event error:', err);
        Swal.fire('Error', 'Terjadi kesalahan saat mengubah status.', 'error');
    }
    };
    window.showEventDetails = async (eventId) => {
    try {
        const token = localStorage.getItem('token');
        const response = await fetch(`http://localhost:3000/api/events/${eventId}`, {
        headers: { Authorization: `Bearer ${token}` }
        });

        const result = await response.json();
        if (!response.ok) throw new Error(result.message || 'Gagal ambil detail');

        const e = result.data;

        document.getElementById('eventDetailContent').innerHTML = `
        <h5>${e.name}</h5>
        <p><strong>Tipe:</strong> ${e.event_type_name}</p>
        <p><strong>Deskripsi:</strong> ${e.description || '-'}</p>
        <p><strong>Waktu:</strong> ${new Date(e.start_date).toLocaleString()} - ${new Date(e.end_date).toLocaleString()}</p>
        <p><strong>Lokasi:</strong> ${e.location || '-'}</p>
        <p><strong>Fee:</strong> Rp${e.registration_fee.toLocaleString()}</p>
        <p><strong>Tipe Registrasi:</strong> ${e.registration_type}</p>
        <p><strong>Sertifikat:</strong> ${e.certificate_type}</p>
        <p><strong>Status:</strong> ${e.is_active ? 'Aktif' : 'Nonaktif'}</p>
        `;

        eventDetailModal.show();
    } catch (err) {
        console.error('Gagal ambil detail event:', err);
        Swal.fire('Gagal', 'Tidak dapat menampilkan detail event.', 'error');
    }
    };

});
</script>
@endpush
