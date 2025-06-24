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
    <!-- Modal Detail Event -->
    <div class="modal fade" id="eventDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Detail Event</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
            <h4 id="detailName" class="mb-2"></h4>
            <p id="detailDescription" class="mb-3 text-muted"></p>
            <ul class="list-group mb-3">
            <li class="list-group-item"><strong>Jenis Event:</strong> <span id="detailType"></span></li>
            <li class="list-group-item"><strong>Lokasi:</strong> <span id="detailLocation"></span></li>
            <li class="list-group-item"><strong>Tanggal:</strong> <span id="detailDate"></span></li>
            <li class="list-group-item"><strong>Fee:</strong> <span id="detailFee"></span></li>
            <li class="list-group-item"><strong>Sertifikat:</strong> <span id="detailCertificate"></span></li>
            </ul>

            <h6>Daftar Panitia:</h6>
            <ul id="committeeList" class="list-group"></ul>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
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
    const detailModal = new bootstrap.Modal(document.getElementById('eventDetailModal'));


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
                    <a href="javascript:void(0)" onclick="showEventDetails(${event.id})">${event.name}</a>
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
        if (!result.success) throw new Error('Gagal mengambil detail event.');

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
