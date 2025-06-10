<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | Event App</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white shadow-lg rounded-2xl flex flex-col md:flex-row w-full max-w-4xl overflow-hidden">
        <!-- Left (Logo Section) -->
        <div class="bg-blue-100 text-white flex items-center justify-center p-8 md:w-1/2">
            <div class="text-center">
                <img src="assets/compiled/png/eventee2.png" alt="Logo" class="w-64 h-64 mx-auto mb-4">
                <!-- <h2 class="text-2xl font-bold">Event App</h2> -->
                <!-- <p class="text-sm opacity-75 mt-2">Sistem Registrasi dan Presensi</p> -->
            </div>
        </div>

        <!-- Right (Form Section) -->
        <div class="p-8 md:w-1/2" x-data="{ tab: 'email' }">
            <div class="text-center mb-6">
                <h2 class="text-3xl font-bold text-gray-800">Masuk</h2>
                <p class="text-gray-500 text-sm">Gunakan Email atau Nomor HP Anda</p>
            </div>

            <div class="flex mb-6 border-b">
                <button class="w-1/2 py-2 text-sm font-semibold"
                    :class="tab === 'email' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-500'"
                    @click="tab = 'email'">
                    Email
                </button>
                <button class="w-1/2 py-2 text-sm font-semibold"
                    :class="tab === 'phone' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-500'"
                    @click="tab = 'phone'">
                    Nomor HP
                </button>
            </div>

            <!-- Form Email -->
            <form id="login-form-email" x-show="tab === 'email'" class="space-y-4" x-cloak>
                @csrf
                <input type="email" name="email" placeholder="Email"
                    class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-blue-500" required>
                <input type="password" name="password" placeholder="Kata Sandi"
                    class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-blue-500" required>
                <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 transition">
                    Masuk dengan Email
                </button>
            </form>

            <!-- Form Phone -->
            <form id="login-form-phone" x-show="tab === 'phone'" class="space-y-4" x-cloak>
                @csrf
                <input type="text" name="phone" placeholder="Nomor HP"
                    class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-blue-500" required>
                <input type="password" name="password" placeholder="Kata Sandi"
                    class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-blue-500" required>
                <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 transition">
                    Masuk dengan Nomor HP
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-gray-500">
                Belum punya akun?
                <a href="/register" class="text-blue-600 hover:underline">Daftar sekarang</a>
            </p>
        </div>
    </div>

    <script>
        const emailForm = document.getElementById('login-form-email');
        const phoneForm = document.getElementById('login-form-phone');

        emailForm?.addEventListener('submit', async function (e) {
            e.preventDefault();
            const email = e.target.email.value;
            const password = e.target.password.value;
            await handleLogin({ email, password });
        });

        phoneForm?.addEventListener('submit', async function (e) {
            e.preventDefault();
            const phone = e.target.phone.value;
            const password = e.target.password.value;
            await handleLogin({ phone, password });
        });

        async function handleLogin(data) {
            try {
                const response = await fetch('http://localhost:3000/api/auth/login', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (response.ok) {
                    const token = result.token;
                    localStorage.setItem('token', token);

                    const payload = JSON.parse(atob(token.split('.')[1]));
                    const roleId = payload.role_id;

                    if (roleId === 1) {
                        window.location.href = '/admin/dashboard';
                    } else if (roleId === 2) {
                        window.location.href = '/finance/dashboard';
                    } else if (roleId === 3) {
                        window.location.href = '/organizer/dashboard';
                    } else if (roleId === 4) {
                        window.location.href = '/member/dashboard';
                    } else {
                        alert('Role tidak dikenali.');
                    }
                } else {
                    alert(result.message || 'Login gagal.');
                }
            } catch (err) {
                console.error('Login Error:', err);
                alert('Terjadi kesalahan jaringan.');
            }
        }
    </script>
</body>
</html>
