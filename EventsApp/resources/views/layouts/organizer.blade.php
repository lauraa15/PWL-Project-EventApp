@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::routeIs('organizer.dashboard') ? 'active' : '' }}" href="{{ route('organizer.dashboard') }}">
                            <i class="bi bi-house-door"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::routeIs('organizer.events.*') ? 'active' : '' }}" href="{{ route('organizer.events.index') }}">
                            <i class="bi bi-calendar-event"></i>
                            Events
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::routeIs('organizer.registrations.*') ? 'active' : '' }}" href="{{ route('organizer.registrations.index') }}">
                            <i class="bi bi-person-check"></i>
                            Registrations
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::routeIs('organizer.certificates.*') ? 'active' : '' }}" href="{{ route('organizer.certificates.index') }}">
                            <i class="bi bi-award"></i>
                            Certificates
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            @yield('organizer-content')
        </main>
    </div>
</div>
@endsection
