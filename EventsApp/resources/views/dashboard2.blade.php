@extends('layouts-horizontal.app')

@section('title', 'Horizontal Layout - Mazer Admin Dashboard')

@section('content')
<div class="content-wrapper container">
    <div class="page-heading">
        <h3>Horizontal Layout</h3>
    </div>
    
    <div class="page-content">
        <section class="row">
            <div class="col-12 col-lg-9">
                {{-- @include('dashboard.partials.stats-cards2') --}}
                {{-- @include('dashboard.partials.profile-visit-chart2') --}}
                {{-- @include('dashboard.partials.visitor-data2') --}}
            </div>
            
            <div class="col-12 col-lg-3">
                {{-- @include('dashboard.partials.user-profile2') --}}
                {{-- @include('dashboard.partials.recent-messages2') --}}
                {{-- @include('dashboard.partials.visitors-profile2') --}}
            </div>
        </section>
    </div>
</div>

@push('scripts')
<script src="{{ asset('assets/extensions/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/static/js/pages/dashboard.js') }}"></script>
@endpush
@endsection