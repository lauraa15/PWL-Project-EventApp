<nav class="main-navbar">
    <div class="container">
        <ul>
            <li class="menu-item">
                <a href="{{ route('dashboard') }}" class='menu-link'>
                    <span><i class="bi bi-grid-fill"></i> Dashboard</span>
                </a>
            </li>
            
            <li class="menu-item has-sub">
                <a href="#" class='menu-link'>
                    <span><i class="bi bi-stack"></i> Components</span>
                </a>
                <div class="submenu">
                    <div class="submenu-group-wrapper">
                        <ul class="submenu-group">
                            <li class="submenu-item">
                                <a href="#" class='submenu-link'>Alert</a>
                            </li>
                            <li class="submenu-item">
                                <a href="#" class='submenu-link'>Badge</a>
                            </li>
                            <!-- Add other component links here -->
                        </ul>
                        <!-- Add other submenu groups here -->
                    </div>
                </div>
            </li>
            
            <!-- Add other menu items here -->
        </ul>
    </div>
</nav>