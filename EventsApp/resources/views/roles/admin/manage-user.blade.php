@extends('layouts.app')

@section('title', 'Event Management Dashboard')

@push('styles')
<link rel="stylesheet" href="assets/compiled/css/simple-datatables.css">
@endpush

@section('content')
<section class="section">
    <div class="card">
        <div class="card-body">
            
         <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <label for="roleFilter" class="form-label">Filter berdasarkan Role</label>
                <select id="roleFilter" class="form-select">
                    <option value="all">Semua Role</option>
                    <option value="Admin">Admin</option>
                    <option value="Finance">Finance</option>
                    <option value="Organizer">Organizer</option>
                    <option value="Member">Member</option>
                </select>
            </div>
            <button class="btn btn-success" onclick="showAddUserCard()">+ Tambah User</button>
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
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="users-body">
                    <!-- Data dari fetch akan dimasukkan di sini -->
                </tbody>
            </table>
            <div id="userFormCard" class="card mt-4" style="display:none;">
                <div class="card-header">Form User</div>
                <div class="card-body">
                    <form id="userForm">
                        <input type="hidden" id="userId">
                        <div class="mb-3">
                            <label>Nama</label>
                            <input type="text" class="form-control" id="name" required>
                        </div>
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" class="form-control" id="email" required>
                        </div>
                        <div class="mb-3">
                            <label>No HP</label>
                            <input type="text" class="form-control" id="phone_number" required>
                        </div>
                        <div class="mb-3">
                            <label>Role</label>
                            <select class="form-select" id="role_name" required>
                                <option value="Admin">Admin</option>
                                <option value="Member">Member</option>
                                <option value="Finance">Finance</option>
                                <option value="Organizer">Organizer</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="password">Password</label>
                            <input type="password" id="password" class="form-control" ${id ? '' : 'required'}>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <button type="button" class="btn btn-secondary" onclick="hideForm()">Batal</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="assets/vendors/simple-datatables/simple-datatables.js"></script>
<script>
    window.users = []; 
    document.addEventListener('DOMContentLoaded', async () => {
        const tbody = document.getElementById('users-body');
        const tableElement = document.querySelector('#table1');
        const roleFilter = document.getElementById('roleFilter');

        try {
            const token = localStorage.getItem('token');
            const response = await fetch('http://localhost:3000/api/users', {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            });

            const result = await response.json();
            window.users = result.users || [];

            renderTable(window.users); // render semua saat awal
            new simpleDatatables.DataTable(tableElement);
        } catch (err) {
            console.error('Gagal memuat data user:', err);
            alert('Terjadi kesalahan saat mengambil data user.');
        }

        roleFilter.addEventListener('change', () => {
            const selectedRole = roleFilter.value;
            const filteredUsers = selectedRole === 'all'
                ? window.users
                : window.users.filter(user => user.role_name === selectedRole);

            renderTable(filteredUsers);
        });

        function renderTable(data) {
            tbody.innerHTML = '';
            data.forEach((user, index) => {
                const status = user.is_active
                    ? '<span class="badge bg-success">Aktif</span>'
                    : '<span class="badge bg-danger">Nonaktif</span>';

                const actionButtons = `
                    <button class="btn btn-sm btn-primary me-1" onclick="editUser(${user.id})">Edit</button>
                    <button class="btn btn-sm ${user.is_active ? 'btn-danger' : 'btn-success'}" onclick="toggleUserStatus(${user.id})">
                        ${user.is_active ? 'Nonaktifkan' : 'Aktifkan'}
                    </button>
                `;

                const row = `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${user.name}</td>
                        <td>${user.email}</td>
                        <td>${user.phone_number}</td>
                        <td>${user.role_name ?? '-'}</td>
                        <td>${status}</td>
                        <td>${actionButtons}</td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
        }
    });

    window.showAddUserCard = () => {
        document.getElementById('userFormCard').style.display = 'block';
        document.getElementById('userForm').reset();
        document.getElementById('userId').value = '';
    };

    window.hideForm = () => {
        document.getElementById('userFormCard').style.display = 'none';
    };

    window.editUser = (id) => {
        const user = window.users.find(u => u.id === id);
        if (user) {
            document.getElementById('userFormCard').style.display = 'block';
            document.getElementById('userId').value = user.id;
            document.getElementById('name').value = user.name;
            document.getElementById('email').value = user.email;
            document.getElementById('phone_number').value = user.phone_number;
            document.getElementById('role_name').value = user.role_name;
        }
    };

    window.toggleUserStatus = async (id) => {
        const token = localStorage.getItem('token');
        try {
            await fetch(`http://localhost:3000/api/users/${id}/toggle`, {
                method: 'PATCH',
                headers: {
                    Authorization: `Bearer ${token}`
                }
            });
            location.reload();
        } catch (err) {
            console.error('Gagal ubah status:', err);
        }
    };

    document.getElementById('userForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const id = document.getElementById('userId').value;
        const url = `http://localhost:3000/api/users${id ? '/' + id : ''}`;
        const method = id ? 'PUT' : 'POST';

        const payload = {
            name: document.getElementById('name').value,
            email: document.getElementById('email').value,
            phone_number: document.getElementById('phone_number').value,
            role_name: document.getElementById('role_name').value,
            password: document.getElementById('password').value
        };

        try {
            await fetch(url, {
                method,
                headers: {
                    'Content-Type': 'application/json',
                    Authorization: `Bearer ${localStorage.getItem('token')}`
                },
                body: JSON.stringify(payload)
            });
            location.reload();
        } catch (err) {
            console.error('Gagal simpan user:', err);
        }
    });
</script>

@endpush
