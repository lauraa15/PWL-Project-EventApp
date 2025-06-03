@extends('layouts-horizontal.app')

@section('title', 'Register - Mazer Admin Dashboard')

@section('content')
<div class="content-wrapper container">
    <form id="register-form">
    <select name="role_id" required>
        <option value="">-- Pilih Role --</option>
        <option value="1">Admin</option>
        <option value="2">Finance</option>
        <option value="3">Organizer</option>
        <option value="4">Member</option>
        <!-- Sesuaikan value dengan yang ada di tabel roles -->
    </select>

    <input type="text" name="name" placeholder="Nama Lengkap" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <input type="text" name="phone_number" placeholder="Nomor HP (Opsional)">

    <button type="submit">Register</button>
</form>

<script>
document.getElementById('register-form').addEventListener('submit', async function (e) {
    e.preventDefault();

    const form = e.target;

    const data = {
        role_id: form.role_id.value,
        name: form.name.value,
        email: form.email.value,
        password: form.password.value,
        phone_number: form.phone_number.value
    };

    const response = await fetch('http://localhost:3000/api/auth/register', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    });

    const result = await response.json();

    if (response.ok) {
        alert(result.message);
        window.location.href = '/login'; // atau ke dashboard kalau langsung login
    } else {
        alert(result.message);
    }
});
</script>
</div>
@endsection
