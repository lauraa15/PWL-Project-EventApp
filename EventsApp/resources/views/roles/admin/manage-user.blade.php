@extends('layouts.app')

@section('title', 'Event Management Dashboard')

@section('styles')
    <style>
        .event-card {
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }
        .event-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .event-status-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
        }
        .event-image {
            height: 180px;
            object-fit: cover;
        }
        .stats-card {
            transition: all 0.3s ease;
        }
        .stats-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .ticket-tier {
            border-left: 4px solid #4e73df;
            padding-left: 10px;
            margin-bottom: 15px;
        }
        .nav-pills .nav-link.active {
            background-color: #4e73df;
        }
    </style>
@endsection

