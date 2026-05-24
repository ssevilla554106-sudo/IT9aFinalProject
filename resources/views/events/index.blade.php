{{-- FILE: resources/views/events/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Browse Events')

@push('styles')
<style>
.hero {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 60%, #0f3460 100%);
    padding: 4rem 0 5rem;
    position: relative;
    overflow: hidden;
}
.hero::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 600px;
    height: 600px;
    background: radial-gradient(circle, rgba(233,69,96,0.15) 0%, transparent 70%);
    border-radius: 50%;
}
.hero-title { font-size: 3.2rem; font-weight: 800; color: white; line-height: 1.1; margin-bottom: 1rem; }
.hero-title span { color: #e94560; }
.hero-sub { color: #94a3b8; font-size: 1.1rem; margin-bottom: 2rem; }

.search-bar {
    display: flex;
    gap: 0.75rem;
    background: white;
    padding: 0.6rem;
    border-radius: 14px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.3);
    max-width: 680px;
}
.search-bar input {
    flex: 1;
    border: none;
    outline: none;
    font-size: 0.95rem;
    padding: 0.4rem 0.75rem;
    font-family: 'DM Sans', sans-serif;
    color: #1e293b;
}
.search-bar button {
    background: #e94560;
    color: white;
    border: none;
    padding: 0.6rem 1.4rem;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    font-family: 'DM Sans', sans-serif;
    display: flex;
    align-items: center;
    gap: 0.4rem;
    transition: background 0.2s;
}
.search-bar button:hover { background: #ff6b8a; }

.featured-badge {
    background: linear-gradient(90deg, #e94560, #f5a623);
    color: white;
    padding: 0.2rem 0.8rem;
    border-radius: 100px;
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.event-card-img {
    height: 200px;
    background: linear-gradient(135deg, #1a1a2e, #0f3460);
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
}
.event-card-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    position: absolute;
    top: 0; left: 0;
}
.event-card-body { padding: 1.5rem; }
.event-card-category {
    font-size: 0.75rem;
    font-weight: 600;
    color: #e94560;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 0.5rem;
}
.event-card-title {
    font-size: 1.1rem;
    font-weight: 700;
    margin-bottom: 0.75rem;
    color: #1e293b;
    line-height: 1.3;
}
.event-meta { font-size: 0.82rem; color: #64748b; display: flex; align-items: center; gap: 0.4rem; margin-bottom: 0.3rem; }
.event-card-footer {
    padding: 1rem 1.5rem;
    border-top: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.price-tag { font-weight: 800; font-family: 'Syne', sans-serif; color: #1e293b; }
.price-free { color: #10b981; }

.filter-bar {
    background: white;
    border-radius: 14px;
    padding: 1.25rem 1.5rem;
    box-shadow: 0 2px 20px rgba(0,0,0,0.06);
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    align-items: flex-end;
    margin-bottom: 2rem;
}
.filter-group { display: flex; flex-direction: column; gap: 0.35rem; min-width: 150px; }
.filter-label { font-size: 0.8rem; font-weight: 600; color: #64748b; }
.filter-select {
    border: 1.5px solid #e2e8f0;
    border-radius: 8px;
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
    font-family: 'DM Sans', sans-serif;
    color: #374151;
    background: white;
    cursor: pointer;
}
.filter-select:focus { outline: none; border-color: #e94560; }

.section-divider {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 2rem;
}
.section-divider h2 { margin: 0; white-space: nowrap; }
.section-divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: #e2e8f0;
}

.empty-state {
    text-align: center;
    padding: 5rem 2rem;
    color: #94a3b8;
}
.empty-state i { font-size: 4rem; margin-bottom: 1rem; display: block; }
.empty-state h3 { font-size: 1.5rem; color: #475569; margin-bottom: 0.5rem; }

.cats-row {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
    margin-bottom: 1.5rem;
}
.cat-pill {
    padding: 0.4rem 1rem;
    border-radius: 100px;
    font-size: 0.82rem;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    border: 1.5px solid #e2e8f0;
    color: #475569;
    transition: all 0.2s;
    background: white;
}
.cat-pill:hover, .cat-pill.active {
    background: #e94560;
    color: white;
    border-color: #e94560;
}
</style>
@endpush

@section('content')
<!-- HERO -->
<div class="hero">
    <div class="container" style="position:relative; z-index:1;">
        <div style="max-width: 700px;">
            <p style="color: #e94560; font-weight: 600; font-size: 0.9rem; margin-bottom: 0.75rem;">
                <i class="fas fa-fire"></i> &nbsp;Discover Amazing Events
            </p>
            <h1 class="hero-title">Find Your Next <span>Unforgettable</span> Experience</h1>
            <p class="hero-sub">Browse thousands of events — from tech conferences to music festivals. Your next adventure starts here.</p>

            <form method="GET" action="{{ route('events.index') }}">
                <div class="search-bar">
                    <i class="fas fa-search" style="color: #94a3b8; padding-left: 0.5rem; display: flex; align-items: center;"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search events, locations, categories...">
                    <button type="submit"><i class="fas fa-search"></i> Search</button>
                </div>
            </form>

            <div style="margin-top: 1.5rem; color: #64748b; font-size: 0.875rem;">
                <i class="fas fa-star" style="color: #f5a623;"></i>
                {{ \App\Models\Event::published()->count() }} events available
            </div>
        </div>
    </div>
</div>

<div class="container section">

    {{-- FEATURED EVENTS --}}
    @if($featuredEvents->count() && !request()->hasAny(['search', 'category', 'date', 'price']))
    <div class="section-divider">
        <h2 class="section-title" style="margin-bottom:0;">
            <i class="fas fa-fire" style="color: #e94560;"></i> Featured Events
        </h2>
    </div>
    <div class="grid-3" style="margin-bottom: 3rem;">
        @foreach($featuredEvents as $event)
        <div class="card">
            <div class="event-card-img">
                @if($event->image_url)
                    <img src="{{ $event->image_url }}" alt="{{ $event->title }}">
                @else
                    {{ ['🎤','💡','🎵','💼','🏆','🎨'][array_rand(['🎤','💡','🎵','💼','🏆','🎨'])] }}
                @endif
                <div style="position:absolute; top:1rem; left:1rem;">
                    <span class="featured-badge"><i class="fas fa-star"></i> Featured</span>
                </div>
            </div>
            <div class="event-card-body">
                <div class="event-card-category">{{ $event->category }}</div>
                <h3 class="event-card-title">{{ $event->title }}</h3>
                <div class="event-meta"><i class="fas fa-calendar"></i> {{ $event->start_date->format('M d, Y') }}</div>
                <div class="event-meta"><i class="fas fa-map-marker-alt"></i> {{ $event->location }}</div>
            </div>
            <div class="event-card-footer">
                <div class="price-tag {{ $event->ticket_price == 0 ? 'price-free' : '' }}">
                    {{ $event->ticket_price == 0 ? 'Free' : '$' . number_format($event->ticket_price, 2) }}
                </div>
                <a href="{{ route('events.show', $event) }}" class="btn btn-primary btn-sm">View Event</a>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- FILTERS --}}
    <form method="GET" action="{{ route('events.index') }}">
        <div class="filter-bar">
            <div class="filter-group" style="flex:1; min-width: 200px;">
                <label class="filter-label">SEARCH</label>
                <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search events..." style="margin: 0;">
            </div>
            <div class="filter-group">
                <label class="filter-label">CATEGORY</label>
                <select name="category" class="filter-select">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label class="filter-label">DATE</label>
                <input type="date" name="date" class="filter-select" value="{{ request('date') }}">
            </div>
            <div class="filter-group">
                <label class="filter-label">PRICE</label>
                <select name="price" class="filter-select">
                    <option value="">Any Price</option>
                    <option value="free" {{ request('price') == 'free' ? 'selected' : '' }}>Free</option>
                    <option value="paid" {{ request('price') == 'paid' ? 'selected' : '' }}>Paid</option>
                </select>
            </div>
            <div>
                <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filter</button>
            </div>
            @if(request()->hasAny(['search', 'category', 'date', 'price']))
            <div>
                <a href="{{ route('events.index') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Clear</a>
            </div>
            @endif
        </div>
    </form>

    {{-- CATEGORY PILLS --}}
    <div class="cats-row">
        <a href="{{ route('events.index') }}" class="cat-pill {{ !request('category') ? 'active' : '' }}">All</a>
        @foreach($categories as $cat)
            <a href="{{ route('events.index', ['category' => $cat]) }}" class="cat-pill {{ request('category') == $cat ? 'active' : '' }}">
                {{ $cat }}
            </a>
        @endforeach
    </div>

    {{-- ALL EVENTS --}}
    <div class="section-divider">
        <h2 class="section-title" style="margin-bottom:0;">
            @if(request('search'))
                Results for "{{ request('search') }}"
            @elseif(request('category'))
                {{ request('category') }} Events
            @else
                All Events
            @endif
        </h2>
    </div>

    @if($events->count())
    <div class="grid-3">
        @foreach($events as $event)
        <div class="card">
            <div class="event-card-img">
                @if($event->image_url)
                    <img src="{{ $event->image_url }}" alt="{{ $event->title }}">
                @else
                    @php
                        $icons = ['Technology' => '💡', 'Business' => '💼', 'Design' => '🎨', 'Health' => '🏃', 'Entertainment' => '🎵', 'Education' => '📚', 'Sports' => '🏆'];
                    @endphp
                    {{ $icons[$event->category] ?? '📅' }}
                @endif
                @if($event->is_featured)
                <div style="position:absolute; top:1rem; left:1rem;">
                    <span class="featured-badge"><i class="fas fa-star"></i> Featured</span>
                </div>
                @endif
                <div style="position:absolute; top:1rem; right:1rem;">
                    <span class="badge badge-{{ $event->status }}">{{ ucfirst($event->status) }}</span>
                </div>
            </div>
            <div class="event-card-body">
                <div class="event-card-category">{{ $event->category }}</div>
                <h3 class="event-card-title">{{ Str::limit($event->title, 50) }}</h3>
                <div class="event-meta"><i class="fas fa-calendar-alt"></i> {{ $event->start_date->format('M d, Y \a\t g:i A') }}</div>
                <div class="event-meta"><i class="fas fa-map-marker-alt"></i> {{ $event->location }}</div>
                @if($event->max_attendees)
                <div class="event-meta">
                    <i class="fas fa-users"></i>
                    {{ $event->attendees_count }}/{{ $event->max_attendees }} registered
                    @if($event->is_full)
                        <span style="color: #ef4444; font-weight: 600;">(Full)</span>
                    @endif
                </div>
                @endif
            </div>
            <div class="event-card-footer">
                <div class="price-tag {{ $event->ticket_price == 0 ? 'price-free' : '' }}">
                    {{ $event->ticket_price == 0 ? '🎟️ Free' : '$' . number_format($event->ticket_price, 2) }}
                </div>
                <a href="{{ route('events.show', $event) }}" class="btn btn-primary btn-sm">View Details</a>
            </div>
        </div>
        @endforeach
    </div>

    {{-- PAGINATION --}}
    <div style="margin-top: 3rem; display: flex; justify-content: center;">
        {{ $events->withQueryString()->links() }}
    </div>

    @else
    <div class="empty-state">
        <i class="fas fa-calendar-times"></i>
        <h3>No Events Found</h3>
        <p>Try adjusting your search or filters.</p>
        <a href="{{ route('events.index') }}" class="btn btn-primary" style="margin-top: 1rem;">Clear Filters</a>
    </div>
    @endif
</div>
@endsection
