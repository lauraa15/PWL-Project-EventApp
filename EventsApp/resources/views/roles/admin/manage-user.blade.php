@extends('layouts.app')

@section('title', 'Event Management Dashboard')

@push('styles')
<link rel="stylesheet" href="assets/compiled/css/simple-datatables.css">
@endpush

@section('content')
<section class="section">
    <div class="card">
        <div class="card-body">
            <table class="table table-striped" id="table1">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>No HP</th>
                        <th>Role</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="users-body">
                    <!-- Data dari fetch akan dimasukkan di sini -->
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="assets/vendors/simple-datatables/simple-datatables.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', async () => {
        const tbody = document.getElementById('users-body');
        const tableElement = document.querySelector('#table1');

        try {
            const token = localStorage.getItem('token');
            const response = await fetch('http://localhost:3000/api/users', {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            });

            const result = await response.json();
            const users = result.users || [];

            users.forEach((user, index) => {
                const status = user.is_active
                    ? '<span class="badge bg-success">Aktif</span>'
                    : '<span class="badge bg-danger">Nonaktif</span>';

                const row = `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${user.name}</td>
                        <td>${user.email}</td>
                        <td>${user.phone_number}</td>
                        <td>${user.role_name ?? '-'}</td>
                        <td>${status}</td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });

            // Inisialisasi DataTable setelah data dimuat
            new simpleDatatables.DataTable(tableElement);

        } catch (err) {
            console.error('Gagal memuat data user:', err);
            alert('Terjadi kesalahan saat mengambil data user.');
        }
    });
</script>
@endpush
