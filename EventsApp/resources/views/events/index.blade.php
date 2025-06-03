@extends('layouts.app')
@section('content')
    <h1>Daftar Event</h1>
    @foreach($events as $event)
        <div class="event-card">
            <h2>{{ $event->name }}</h2>
            <p>{{ $event->description }}</p>
            <a href="/events/{{ $event->id }}">Detail</a>
        </div>
    @endforeach
@endsection