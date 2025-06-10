@extends('layouts-horizontal.app-login')

@section('title', 'EventHub - Discover Amazing Events')

@section('styles')

@endsection

@section('content')
    <!-- Hero Section -->
    <section class="hero-section card text-center p-5 border-top border-primary" style="border-radius: 0">
        <div class="container card-content">
            <h1 class="display-4 fw-bold mb-4">Discover Your Next Experience</h1>
            <p class="lead mb-5">Find and book tickets for concerts, sports, theater and more</p>
            
            <div class="search-bar card-body">
                <div class="input-group">
                    <input type="text" class="form-control form-control-lg" placeholder="Search for events...">
                    <button class="btn btn-primary" type="button">
                        <i class="bi bi-search"></i> Search
                    </button>
                </div>
            </div>
        </div>
        <!-- Events Carousel -->
        <section class="container mb-5">
            <div id="eventsCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner rounded">
                    <div class="carousel-item active">
                        <img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87" class="d-block w-100" alt="Event 1" style="height: 400px; object-fit: cover;">
                        <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-3">
                            <h5>Summer Music Festival</h5>
                            <p>Join us for the biggest music event of the year!</p>
                            <a href="#" class="btn btn-primary">Get Tickets</a>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="https://images.unsplash.com/photo-1475721027785-f74eccf877e2" class="d-block w-100" alt="Event 2" style="height: 400px; object-fit: cover;">
                        <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-3">
                            <h5>Tech Conference 2025</h5>
                            <p>Learn from industry leaders about the latest technologies.</p>
                            <a href="#" class="btn btn-primary">Get Tickets</a>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="https://images.unsplash.com/photo-1492684223066-81342ee5ff30" class="d-block w-100" alt="Event 3" style="height: 400px; object-fit: cover;">
                        <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-3">
                            <h5>Food & Wine Expo</h5>
                            <p>Taste the finest cuisine from around the world.</p>
                            <a href="#" class="btn btn-primary">Get Tickets</a>
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#eventsCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#eventsCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </section>
    </section>

    

    <!-- Top Upcoming Events -->
    <section class="container mb-5">
        <h2 class="section-title">Top Upcoming Events</h2>
        <div class="row">
            @for($i = 0; $i < 4; $i++)
            <div class="col-md-3">
                <div class="card event-card">
                    {{-- <img src="https://source.unsplash.com/random/300x200?event={{ $i }}" class="card-img-top" alt="Event"> --}}
                    <img src="https://www.maranatha.edu/wp-content/uploads/2023/12/General-Banner-Digital-1.png" class="card-img-top" alt="Event">
                    <span class="category-badge">Festival</span>
                    <div class="card-body">
                        <h5 class="card-title">Google Developer Festival {{ $i + 1 }}</h5>
                        <p class="card-text text-muted">
                            <i class="bi bi-calendar-event"></i> December {{ 15 + $i }}, 2025<br>
                            <i class="bi bi-geo-alt"></i> Bandung, Jawa Barat
                        </p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-primary">${{ 20 + ($i * 5) }}</span>
                            <a href="#" class="btn btn-sm btn-outline-primary">Details</a>
                        </div>
                    </div>
                </div>
            </div>
            @endfor
        </div>
        <div class="text-center mt-4">
            <a href="#" class="btn btn-outline-primary">View All Upcoming Events</a>
        </div>
    </section>

    <!-- Events by Genre -->
    <section class="container mb-5">
        <h2 class="section-title">Recommended by Genre</h2>
        <ul class="nav nav-tabs mb-4" id="genreTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="music-tab" data-bs-toggle="tab" data-bs-target="#music" type="button" role="tab">Music</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="sports-tab" data-bs-toggle="tab" data-bs-target="#sports" type="button" role="tab">Sports</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="arts-tab" data-bs-toggle="tab" data-bs-target="#arts" type="button" role="tab">Arts</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="food-tab" data-bs-toggle="tab" data-bs-target="#food" type="button" role="tab">Food & Drink</button>
            </li>
        </ul>
        <div class="tab-content" id="genreTabsContent">
            <div class="tab-pane fade show active" id="music" role="tabpanel">
                <div class="row">
                    @for($i = 0; $i < 4; $i++)
                    <div class="col-md-3">
                        <div class="card event-card">
                            <img src="https://seremonia.id/wp-content/uploads/2023/06/348462995_646357986860327_7169356600016996700_n.jpg" class="card-img-top" alt="Music Event">
                            <div class="card-body">
                                <h5 class="card-title">Live Concert {{ $i + 1 }}</h5>
                                <p class="card-text text-muted">
                                    <i class="bi bi-calendar-event"></i> July {{ 5 + $i }}, 2025<br>
                                    <i class="bi bi-geo-alt"></i> Music Hall, NY
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold text-primary">${{ 30 + ($i * 5) }}</span>
                                    <a href="#" class="btn btn-sm btn-outline-primary">Details</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endfor
                </div>
            </div>
            <div class="tab-pane fade" id="sports" role="tabpanel">
                <div class="row">
                    @for($i = 0; $i < 4; $i++)
                    <div class="col-md-3">
                        <div class="card event-card">
                            <img src="https://source.unsplash.com/random/300x200?sports={{ $i }}" class="card-img-top" alt="Sports Event">
                            <div class="card-body">
                                <h5 class="card-title">Sports Game {{ $i + 1 }}</h5>
                                <p class="card-text text-muted">
                                    <i class="bi bi-calendar-event"></i> August {{ 10 + $i }}, 2025<br>
                                    <i class="bi bi-geo-alt"></i> Stadium, NY
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold text-primary">${{ 40 + ($i * 5) }}</span>
                                    <a href="#" class="btn btn-sm btn-outline-primary">Details</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endfor
                </div>
            </div>
            <div class="tab-pane fade" id="arts" role="tabpanel">
                <div class="row">
                    @for($i = 0; $i < 4; $i++)
                    <div class="col-md-3">
                        <div class="card event-card">
                            <img src="https://source.unsplash.com/random/300x200?art={{ $i }}" class="card-img-top" alt="Arts Event">
                            <div class="card-body">
                                <h5 class="card-title">Art Exhibition {{ $i + 1 }}</h5>
                                <p class="card-text text-muted">
                                    <i class="bi bi-calendar-event"></i> September {{ 15 + $i }}, 2025<br>
                                    <i class="bi bi-geo-alt"></i> Gallery, NY
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold text-primary">${{ 20 + ($i * 5) }}</span>
                                    <a href="#" class="btn btn-sm btn-outline-primary">Details</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endfor
                </div>
            </div>
            <div class="tab-pane fade" id="food" role="tabpanel">
                <div class="row">
                    @for($i = 0; $i < 4; $i++)
                    <div class="col-md-3">
                        <div class="card event-card">
                            <img src="https://source.unsplash.com/random/300x200?food={{ $i }}" class="card-img-top" alt="Food Event">
                            <div class="card-body">
                                <h5 class="card-title">Food Festival {{ $i + 1 }}</h5>
                                <p class="card-text text-muted">
                                    <i class="bi bi-calendar-event"></i> October {{ 5 + $i }}, 2025<br>
                                    <i class="bi bi-geo-alt"></i> Downtown, NY
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold text-primary">${{ 15 + ($i * 5) }}</span>
                                    <a href="#" class="btn btn-sm btn-outline-primary">Details</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endfor
                </div>
            </div>
        </div>
    </section>

    <!-- Events by Type -->
    <section class="container mb-5">
        <h2 class="section-title">Recommended by Type</h2>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-music-note-beamed display-4 text-primary mb-3"></i>
                        <h3 class="card-title">Concerts</h3>
                        <p class="card-text">Find tickets to your favorite artists and bands.</p>
                        <a href="#" class="btn btn-outline-primary">Browse Concerts</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-trophy display-4 text-primary mb-3"></i>
                        <h3 class="card-title">Sports</h3>
                        <p class="card-text">Don't miss the big game - get your tickets now.</p>
                        <a href="#" class="btn btn-outline-primary">Browse Sports</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-film display-4 text-primary mb-3"></i>
                        <h3 class="card-title">Theater</h3>
                        <p class="card-text">Broadway shows, plays, and performances.</p>
                        <a href="#" class="btn btn-outline-primary">Browse Theater</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="hero-section card text-center py-5 mb-5">
        <div class="container text-center">
            <h2 class="mb-4">Ready to Find Your Next Experience?</h2>
            <p class="lead mb-4">Join thousands of happy attendees at events they love.</p>
            <a href="#" class="btn btn-primary btn-lg me-2">Browse Events</a>
            <a href="#" class="btn btn-outline-primary btn-lg">Create an Account</a>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        // Initialize carousel
        document.addEventListener('DOMContentLoaded', function() {
            const carousel = new bootstrap.Carousel(document.getElementById('eventsCarousel'), {
                interval: 5000,
                ride: 'carousel'
            });
            
            // You can add more JavaScript interactions here
        });
    </script>
@endsection
