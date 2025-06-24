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
            <button class="btn btn-primary mb-3" onclick="showAddUserModal()">+ Tambah User</button>
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
           

        </div>
    </div>
    <div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
            <form id="userForm">
                <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Tambah/Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="userId">
                    <div class="mb-3">
                    <label for="name" class="form-label">Nama</label>
                    <input type="text" class="form-control" id="name" required>
                    </div>
                    <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" required>
                    </div>
                    <div class="mb-3">
                    <label for="phone_number" class="form-label">No HP</label>
                    <input type="text" class="form-control" id="phone_number" required>
                    </div>
                    <div class="mb-3">
                    <label for="role_name" class="form-label">Role</label>
                    <select class="form-select" id="role_name" required>
                        <!-- <option value="admin">Admin</option> -->
                        <option value="finance">Finance</option>
                        <option value="organizer">Organizer</option>
                        <!-- <option value="member">Member</option> -->
                    </select>
                    </div>
                     <div class="mb-3" id="passwordGroup">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="confirmCancel()">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
            </div>
        </div>
        </div>
</section>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<script src="assets/vendors/simple-datatables/simple-datatables.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    window.users = []; 
    let isCreating = true;
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

    const userModal = new bootstrap.Modal(document.getElementById('userModal'));

    window.showAddUserModal = () => {
        isCreating = true;
        document.getElementById('userForm').reset();
        document.getElementById('userId').value = '';
        document.getElementById('modalTitle').textContent = 'Tambah User';
        document.getElementById('passwordGroup').style.display = 'block';
        document.getElementById('password').required = true;
        userModal.show();
    };

    window.editUser = (id) => {
        isCreating = false;
        const user = users.find(u => u.id === id);
        if (user) {
            document.getElementById('userId').value = user.id;
            document.getElementById('name').value = user.name;
            document.getElementById('email').value = user.email;
            document.getElementById('phone_number').value = user.phone_number;
            document.getElementById('role_name').value = user.role_name?.toLowerCase();
            document.getElementById('modalTitle').textContent = 'Edit User';

            document.getElementById('passwordGroup').style.display = 'none';
            document.getElementById('password').required = false;
            userModal.show();
            }
        };

    window.toggleUserStatus = async (id) => {
        console.log('Masuk toggleUserStatus');
        const token = localStorage.getItem('token');
        try {
            const response = await fetch(`http://localhost:3000/api/users/${id}/toggle`, {
            method: 'PATCH',
            headers: {
                Authorization: `Bearer ${token}`
            }
            });

            const result = await response.json();

            Swal.fire({
            icon: 'success',
            title: result.message,
            timer: 1500,
            showConfirmButton: false
            });

            setTimeout(() => location.reload(), 1600);
        } catch (err) {
            console.error('Gagal ubah status:', err);
            Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: 'Tidak bisa mengubah status user.'
            });
        }
        };

    window.confirmCancel = () => {
        Swal.fire({
            title: 'Yakin batal?',
            text: "Perubahan yang belum disimpan akan hilang.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, batal',
            cancelButtonText: 'Kembali'
        }).then((result) => {
            if (result.isConfirmed) {
                userModal.hide();
            }
        });
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
            role_name: document.getElementById('role_name').value
            // password: document.getElementById('password').value
        };
        if (isCreating) {
        payload.password = document.getElementById('password').value;
    }


        try {
            await fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                Authorization: `Bearer ${localStorage.getItem('token')}`
            },
            body: JSON.stringify(payload)
        });

        userModal.hide();

        Swal.fire({
            icon: 'success',
            title: isCreating ? 'User berhasil ditambahkan!' : 'User berhasil diperbarui!',
            showConfirmButton: false,
            timer: 1500
        });

        setTimeout(() => {
            location.reload();
        }, 1600);
    } catch (err) {
        console.error('Gagal simpan user:', err);
        Swal.fire({
            icon: 'error',
            title: 'Gagal menyimpan user!',
            text: 'Terjadi kesalahan saat menyimpan data.',
        });
    }
    });
</script>

@endpush
