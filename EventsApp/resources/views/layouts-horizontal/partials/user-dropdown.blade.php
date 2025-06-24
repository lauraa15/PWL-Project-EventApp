<div class="dropdown">
    <a href="#" id="topbarUserDropdown" class="user-dropdown d-flex align-items-center dropend dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
        <div class="avatar avatar-md2">
            <img src="{{ asset('assets/compiled/jpg/1.jpg') }}" alt="Avatar">
        </div>
        <div class="text">
            <h6 class="user-dropdown-name" id="user-name">Loading...</h6>
            <p class="user-dropdown-status text-sm text-muted" id="user-role">-</p>
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

function parseJwt(token) {
    try {
        const base64Url = token.split('.')[1];
        const base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
        const jsonPayload = decodeURIComponent(atob(base64).split('').map(c =>
            '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2)
        ).join(''));
        return JSON.parse(jsonPayload);
    } catch (e) {
        return null;
    }
}

function getRoleLabel(roleId) {
    switch (roleId) {
        case 1: return 'Administrator';
        case 2: return 'Keuangan';
        case 3: return 'Panitia';
        case 4: return 'Member';
        default: return 'Guest';
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const user = parseJwt(token);
    if (!user) {
        localStorage.removeItem('token');
        return window.location.href = '/login';
    }

    document.getElementById('user-name').innerText = user.name || 'Pengguna';
    document.getElementById('user-role').innerText = getRoleLabel(user.role_id);
});

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
            localStorage.removeItem('token');
            Swal.fire({
                title: 'Berhasil logout!',
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
            });
            setTimeout(() => {
                window.location.href = '/';
            }, 1500);
        }
    });
});
</script>
