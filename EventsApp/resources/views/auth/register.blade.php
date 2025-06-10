<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register | Event App</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white shadow-lg rounded-2xl flex flex-col md:flex-row w-full max-w-4xl overflow-hidden">
        <!-- Left (Logo Section) -->
        <div class="bg-blue-100 text-white flex items-center justify-center p-8 md:w-1/2">
            <div class="text-center">
                <img src="assets/compiled/png/eventee2.png" alt="Logo" class="w-64 h-64 mx-auto mb-4">
                <!-- <h2 class="text-2xl font-bold">Event App</h2>
                <p class="text-sm opacity-75 mt-2">Daftarkan akun barumu sekarang</p> -->
            </div>
        </div>

        <!-- Right (Register Form) -->
        <div class="p-8 md:w-1/2">
            <div class="text-center mb-6">
                <h2 class="text-3xl font-bold text-gray-800">Daftar</h2>
                <p class="text-gray-500 text-sm">Silakan isi data di bawah ini</p>
            </div>

            <form id="register-form" class="space-y-4">
                @csrf
                <input type="text" name="name" placeholder="Nama Lengkap" required
                    class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-blue-500">
                <input type="email" name="email" placeholder="Email" required
                    class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-blue-500">
                <input type="text" name="phone_number" placeholder="Nomor HP" required
                    class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-blue-500">
                <input type="password" name="password" placeholder="Password" required
                    class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-blue-500">
                <input type="password" name="password_confirmation" placeholder="Konfirmasi Password" required
                    class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-blue-500">
                <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 transition">
                    Daftar
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-gray-500">
                Sudah punya akun?
                <a href="/login" class="text-blue-600 hover:underline">Masuk di sini</a>
            </p>
        </div>
    </div>

    <script>
        document.getElementById('register-form').addEventListener('submit', async function(e) {
            e.preventDefault();

            const name = e.target.name.value;
            const email = e.target.email.value;
            const phone_number = e.target.phone_number.value;
            const password = e.target.password.value;
            const password_confirmation = e.target.password_confirmation.value;

            if (password !== password_confirmation) {
                alert('Password dan konfirmasi tidak cocok.');
                return;
            }

            try {
                const response = await fetch('http://localhost:3000/api/auth/register', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ name, email, phone_number, password })
                });

                const result = await response.json();

                if (response.ok) {
                    alert('Registrasi berhasil! Silakan login.');
                    window.location.href = '/login';
                } else {
                    alert(result.message || 'Registrasi gagal.');
                }
            } catch (err) {
                console.error('Register Error:', err);
                alert('Terjadi kesalahan jaringan.');
            }
        });
    </script>
</body>
</html>
