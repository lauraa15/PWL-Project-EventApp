@extends('layouts.app')

@section('title', 'Manajemen Registrasi - Finance')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" />
@endpush

@section('content')
<section class="section">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Data Pendaftaran Event</h4>
            <table class="table table-striped" id="finance-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Peserta</th>
                        <th>Event</th>
                        <th>Tanggal Daftar</th>
                        <th>Status</th>
                        <th>QR Code</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="finance-body">
                    <!-- Diisi melalui JavaScript -->
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" defer></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>=

<script>
document.addEventListener('DOMContentLoaded', async () => {
    const tbody = document.getElementById('finance-body');
    const table = document.getElementById('finance-table');
    const token = localStorage.getItem('token');
    const selectedEventId = 1;

    try {
        const res = await fetch(`http://localhost:3000/api/finance/registrations?event_id=${selectedEventId}`, {
            headers: {
                Authorization: `Bearer ${token}`
            }
            });

        const result = await res.json();
        if (!result.success) throw new Error('Gagal ambil data registrasi');

        const registrations = result.data;
        tbody.innerHTML = '';

        registrations.forEach((reg, index) => {
            const row = document.createElement('tr');

            row.innerHTML = `
                <td>${index + 1}</td>
                <td>${reg.user_name}</td>
                <td>${reg.event_name}</td>
                <td>${new Date(reg.registration_date).toLocaleDateString()}</td>
                <td>${reg.status}</td>
                <td>
                    ${reg.qr_code ? `<img src="${reg.qr_code}" alt="QR" width="50">` : '-'}
                </td>
                <td>
                    ${reg.status === 'pending' ? `
                        <button class="btn btn-sm btn-success" onclick="approvePayment('${reg.id}')">Approve</button>
                        <button class="btn btn-danger btn-sm" onclick="rejectPayment(${reg.id})">Tolak</button>
                    ` : '-'}
                </td>
            `;

            tbody.appendChild(row);
        });

        new simpleDatatables.DataTable(table);
    } catch (err) {
        console.error('Error:', err);
        Swal.fire('Error', 'Gagal memuat data registrasi', 'error');
    }

    window.approvePayment = async (registrationId) => {
    const token = localStorage.getItem('token');
    const confirmed = await Swal.fire({
        title: 'Setujui Pembayaran?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, setujui!',
    });

    if (!confirmed.isConfirmed) return;

    try {
        const response = await fetch(`http://localhost:3000/api/payments/${registrationId}/approve`, {
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
        console.error('Approve error:', err);
        Swal.fire('Error', 'Terjadi kesalahan saat approval.', 'error');
    }
    };
    window.rejectPayment = async (registrationId) => {
    const token = localStorage.getItem('token');
    const confirmed = await Swal.fire({
        title: 'Tolak Pembayaran?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, tolak',
        cancelButtonText: 'Batal'
    });

    if (!confirmed.isConfirmed) return;

    try {
        const response = await fetch(`http://localhost:3000/api/payments/${registrationId}/reject`, {
        method: 'PATCH',
        headers: {
            Authorization: `Bearer ${token}`
        }
        });

        const result = await response.json();
        if (response.ok) {
        Swal.fire('Ditolak!', result.message, 'success');
        location.reload();
        } else {
        Swal.fire('Gagal!', result.message, 'error');
        }
    } catch (err) {
        console.error('Reject error:', err);
        Swal.fire('Error', 'Terjadi kesalahan saat penolakan.', 'error');
    }
    };
});
</script>
@endpush
