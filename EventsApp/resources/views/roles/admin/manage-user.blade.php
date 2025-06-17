@extends('layouts.app')

@section('title', 'Event Management Dashboard')

@push('styles')
<link rel="stylesheet" href="assets/compiled/css/simple-datatables.css">
@endpush

@section('content')
<section class="section">
    <div class="card">
        <div class="card-body">
          <div class="mb-3">
            <label for="roleFilter" class="form-label">Filter berdasarkan Role</label>
            <select id="roleFilter" class="form-select">
                <option value="all">Semua Role</option>
                <option value="Admin">Admin</option>
                <option value="Finance">Finance</option>
                <option value="Organizer">Organizer</option>
                <option value="Member">Member</option>
                <!-- Tambahkan opsi lain sesuai kebutuhan -->
            </select>
        </div>
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
    const roleFilter = document.getElementById('roleFilter');

    let users = [];

    try {
        const token = localStorage.getItem('token');
        const response = await fetch('http://localhost:3000/api/users', {
            headers: {
                Authorization: `Bearer ${token}`
            }
        });

        const result = await response.json();
        users = result.users || [];

        renderTable(users); // render semua saat awal

        new simpleDatatables.DataTable(tableElement);
    } catch (err) {
        console.error('Gagal memuat data user:', err);
        alert('Terjadi kesalahan saat mengambil data user.');
    }

    // Event: ketika filter dipilih
    roleFilter.addEventListener('change', () => {
        const selectedRole = roleFilter.value;

        const filteredUsers = selectedRole === 'all'
            ? users
            : users.filter(user => user.role_name === selectedRole);

        renderTable(filteredUsers);
    });

    function renderTable(data) {
        tbody.innerHTML = '';
        data.forEach((user, index) => {
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
    }
});
</script>
@endpush
