<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<div id="sidebar">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header position-relative">
            <div class="d-flex justify-content-between align-items-center">
                <div class="logo">
                    <a href="{{ url('/') }}"><img height="70" src="{{ asset('assets/compiled/png/eventee2.png') }}" alt="Logo" srcset=""></a>
                </div>
                <div class="theme-toggle d-flex gap-2 align-items-center mt-2">
                    <!-- Theme toggle SVG -->
                    <div class="form-check form-switch fs-6">
                        <input class="form-check-input me-0" type="checkbox" id="toggle-dark" style="cursor: pointer">
                        <label class="form-check-label"></label>
                    </div>
                </div>
                <div class="sidebar-toggler x">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                </div>
            </div>
        </div>
        <div class="sidebar-menu">
            <ul class="menu">
                <li class="sidebar-title">Menu</li>
                
                <li class="sidebar-item {{ request()->is('/') ? 'active' : '' }}">
                    <a href="{{ url('/') }}" class='sidebar-link'>
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-item {{ request()->is('admin/manage-user') ? 'active' : '' }}">
                    <a href="{{ url('admin/manage-user') }}" class='sidebar-link'>
                        <i class="bi bi-person-fill"></i>
                        <span>User Data</span>
                    </a>
                </li>
                
                <!-- Components Menu -->
                <li class="sidebar-item has-sub">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-stack"></i>
                        <span>Components</span>
                    </a>
                    <ul class="submenu">
                        <li class="submenu-item">
                            <a href="{{ route('component.accordion') }}" class="submenu-link">Accordion</a>
                        </li>
                        <!-- Other component links -->
                    </ul>
                </li>
         
                <li class="sidebar-item {{ request()->is('/') ? 'active' : '' }}">
                    <a href="#" class='sidebar-link' id="logout-button">
                        <i class="bi bi-person-badge-fill"></i>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
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
