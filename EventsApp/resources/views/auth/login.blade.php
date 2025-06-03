<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container mt-5" style="max-width: 400px;">
    <h3 class="mb-4">Login</h3>

    <form id="loginForm">
        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" id="email" name="email" class="form-control" required />
        </div>
        
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" id="password" name="password" class="form-control" required />
        </div>
        
        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>

    <div id="message" class="mt-3"></div>
</div>

<script>
    const form = document.getElementById('loginForm');
    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        const email = form.email.value;
        const password = form.password.value;

        try {
            const response = await fetch('http://localhost:3000/api/auth/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ email, password }),
            });

            const data = await response.json();

            const messageDiv = document.getElementById('message');

            if (response.ok) {
                messageDiv.innerHTML = `<div class="alert alert-success">Login berhasil! Token: ${data.token}</div>`;
                // Simpan token di localStorage atau cookie untuk dipakai selanjutnya
                localStorage.setItem('token', data.token);
                // Redirect ke dashboard atau halaman lain kalau perlu
            } else {
                messageDiv.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
            }
        } catch (error) {
            console.error('Error:', error);
        }
    });
</script>

</body>
</html> -->

@extends('layouts-horizontal.app')

@section('title', 'Horizontal Layout - Mazer Admin Dashboard')

@section('content')
<div class="content-wrapper container">

<!-- resources/views/auth/login.blade.php -->
    <form method="POST" id="login-form">
        @csrf
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>

    <script>
    document.getElementById('login-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const email = e.target.email.value;
        const password = e.target.password.value;

        const response = await fetch('http://localhost:3000/api/auth/login', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email, password })
        });

        const result = await response.json();

        if (response.ok) {
            // Simpan token JWT di localStorage / cookie
            localStorage.setItem('token', result.token);

            // Redirect ke dashboard Laravel
            window.location.href = '/dashboard';
        } else {
            alert(result.message || 'Login gagal.');
        }
    });
    </script>
</div>
@endsection
