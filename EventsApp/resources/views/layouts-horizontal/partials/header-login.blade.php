<header class="mb-0">
    <div class="header-top">
        <div class="container">
            <div class="logo">
                <a href="{{ route('dashboard') }}"><img src="{{ asset('assets/compiled/png/eventee2.png') }}" alt="Logo"></a>
            </div>

            <div class="header-top-right">
                @include('layouts-horizontal.partials.user-register')
                <!-- Burger button responsive -->
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </div>
        </div>
    </div>

    @include('layouts-horizontal.partials.navbar')
</header>
