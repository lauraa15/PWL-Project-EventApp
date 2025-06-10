<div class="dropdown">
    <a href="#" id="topbarUserDropdown" class="user-dropdown d-flex align-items-center dropend dropdown-toggle " data-bs-toggle="dropdown" aria-expanded="false">
        <div class="avatar avatar-md2">
            <img src="{{ asset('assets/compiled/jpg/1.jpg') }}" alt="Avatar">
        </div>
        <div class="text">
            <h6 class="user-dropdown-name">John Ducky</h6>
            <p class="user-dropdown-status text-sm text-muted">Member</p>
        </div>
    </a>
    <ul class="dropdown-menu dropdown-menu-end shadow-lg" aria-labelledby="topbarUserDropdown">
        <li><a class="dropdown-item" href="#">My Account</a></li>
        <li><a class="dropdown-item" href="#">Settings</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a href="#" class='sidebar-link' id="logout-button">Logout</a></li>
    </ul>
</div>

<script>
    const token = localStorage.getItem('token');
    if (!token) {
        window.location.href = '/login';
    }
</script>
<script>
    document.getElementById('logout-button').addEventListener('click', function (e) {
        e.preventDefault();

        Swal.fire({
            title: 'Yakin ingin logout?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, logout',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Hapus token dari localStorage
                localStorage.removeItem('token');

                // Tampilkan notifikasi sukses
                Swal.fire({
                    title: 'Berhasil logout!',
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                });

                // Redirect setelah 1.5 detik
                setTimeout(() => {
                    window.location.href = '/login';
                }, 1500);
            }
        });
    });
</script>
