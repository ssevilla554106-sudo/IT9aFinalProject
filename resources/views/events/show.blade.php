{{-- FILE: resources/views/events/show.blade.php --}}
@extends('layouts.app')

@section('title', $event->title)

@push('styles')
<style>
.event-hero {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
    color: white;
    padding: 3rem 0;
}
.event-hero-img {
    width: 100%;
    height: 400px;
    object-fit: cover;
    border-radius: 20px;
    margin-bottom: 2rem;
}
.event-hero-placeholder {
    width: 100%;
    height: 300px;
    background: linear-gradient(135deg, #0f3460, #16213e);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 6rem;
    margin-bottom: 2rem;
}
.detail-card {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 2px 20px rgba(0,0,0,0.06);
    margin-bottom: 1.5rem;
}
.detail-row {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 0.9rem 0;
    border-bottom: 1px solid #f1f5f9;
    font-size: 0.95rem;
}
.detail-row:last-child { border-bottom: none; }
.detail-icon {
    width: 36px; height: 36px;
    background: #fff0f3;
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    color: #e94560;
    flex-shrink: 0;
}
.detail-label { font-size: 0.75rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; }
.detail-value { font-weight: 600; color: #1e293b; }

.register-box {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 2px 20px rgba(0,0,0,0.06);
    position: sticky;
    top: 90px;
}
.price-display {
    font-size: 2.5rem;
    font-weight: 800;
    font-family: 'Syne', sans-serif;
    color: #1e293b;
    margin-bottom: 0.25rem;
}
.price-free-display { color: #10b981; }
.spots-bar {
    background: #f1f5f9;
    border-radius: 100px;
    height: 8px;
    overflow: hidden;
    margin: 1rem 0;
}
.spots-fill {
    height: 100%;
    background: linear-gradient(90deg, #10b981, #059669);
    border-radius: 100px;
    transition: width 0.3s;
}
.spots-fill.almost-full { background: linear-gradient(90deg, #f59e0b, #d97706); }
.spots-fill.full { background: linear-gradient(90deg, #ef4444, #dc2626); }

.organizer-card {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.25rem;
    background: #f8fafc;
    border-radius: 12px;
}
.organizer-avatar {
    width: 48px; height: 48px;
    border-radius: 12px;
    background: linear-gradient(135deg, #e94560, #0f3460);
    display: flex; align-items: center; justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 1.2rem;
    flex-shrink: 0;
}
</style>
@endpush

@section('content')
<div class="event-hero">
    <div class="container">
        <div style="margin-bottom: 0.75rem;">
            <a href="{{ route('events.index') }}" style="color: #94a3b8; text-decoration: none; font-size: 0.875rem;">
                <i class="fas fa-arrow-left"></i> Back to Events
            </a>
        </div>
        <span class="badge badge-{{ $event->status }}" style="margin-bottom: 1rem; display: inline-flex;">{{ ucfirst($event->status) }}</span>
        @if($event->is_featured)
            <span class="featured-badge" style="margin-left: 0.5rem; background: linear-gradient(90deg, #e94560, #f5a623); color: white; padding: 0.2rem 0.8rem; border-radius: 100px; font-size: 0.7rem; font-weight: 700;">
                <i class="fas fa-star"></i> Featured
            </span>
        @endif
        <h1 style="font-size: 2.5rem; font-weight: 800; margin: 0.75rem 0 0.5rem;">{{ $event->title }}</h1>
        <p style="color: #94a3b8; font-size: 1rem;">
            <i class="fas fa-tag" style="color: #e94560;"></i> {{ $event->category }}
            &nbsp;&nbsp;<i class="fas fa-user"></i> Organized by {{ $event->user->name }}
        </p>
    </div>
</div>

<div class="container" style="padding-top: 2.5rem;">
    <div style="display: grid; grid-template-columns: 1fr 380px; gap: 2rem; align-items: start;">

        {{-- LEFT COLUMN --}}
        <div>
            {{-- IMAGE --}}
            @if($event->image_url)
                <img src="{{ $event->image_url }}" alt="{{ $event->title }}" class="event-hero-img">
            @else
                @php
                    $icons = ['Technology' => '💡', 'Business' => '💼', 'Design' => '🎨', 'Health' => '🏃', 'Entertainment' => '🎵', 'Education' => '📚', 'Sports' => '🏆'];
                @endphp
                <div class="event-hero-placeholder">{{ $icons[$event->category] ?? '📅' }}</div>
            @endif

            {{-- DESCRIPTION --}}
            <div class="detail-card">
                <h2 style="font-size: 1.4rem; font-weight: 700; margin-bottom: 1rem;">About This Event</h2>
                <div style="color: #475569; line-height: 1.8; white-space: pre-wrap;">{{ $event->description }}</div>
            </div>

            {{-- ORGANIZER --}}
            <div class="detail-card">
                <h2 style="font-size: 1.2rem; font-weight: 700; margin-bottom: 1rem;">Organizer</h2>
                <div class="organizer-card">
                    <div class="organizer-avatar">{{ strtoupper(substr($event->user->name, 0, 1)) }}</div>
                    <div>
                        <div style="font-weight: 700;">{{ $event->user->name }}</div>
                        <div style="font-size: 0.85rem; color: #64748b;">Event Organizer</div>
                    </div>
                </div>
            </div>

            {{-- RELATED EVENTS --}}
            @if($relatedEvents->count())
            <div class="detail-card">
                <h2 style="font-size: 1.2rem; font-weight: 700; margin-bottom: 1.25rem;">Similar Events</h2>
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    @foreach($relatedEvents as $related)
                    <a href="{{ route('events.show', $related) }}" style="text-decoration: none;">
                        <div style="display: flex; gap: 1rem; padding: 1rem; background: #f8fafc; border-radius: 12px; transition: background 0.2s;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#f8fafc'">
                            <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #1a1a2e, #0f3460); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0;">
                                {{ $icons[$related->category] ?? '📅' }}
                            </div>
                            <div>
                                <div style="font-weight: 600; color: #1e293b; font-size: 0.95rem;">{{ $related->title }}</div>
                                <div style="font-size: 0.8rem; color: #64748b; margin-top: 0.2rem;">
                                    <i class="fas fa-calendar"></i> {{ $related->start_date->format('M d, Y') }}
                                    &nbsp;·&nbsp;
                                    {{ $related->ticket_price == 0 ? 'Free' : '$' . number_format($related->ticket_price, 2) }}
                                </div>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        {{-- RIGHT COLUMN (REGISTER BOX) --}}
        <div>
            <div class="register-box">
                <div class="price-display {{ $event->ticket_price == 0 ? 'price-free-display' : '' }}">
                    {{ $event->ticket_price == 0 ? 'Free' : '$' . number_format($event->ticket_price, 2) }}
                </div>
                <div style="font-size: 0.85rem; color: #94a3b8; margin-bottom: 1.5rem;">
                    {{ $event->ticket_price == 0 ? 'No charge to attend' : 'Per person' }}
                </div>

                {{-- SPOTS --}}
                @if($event->max_attendees)
                <div style="margin-bottom: 1.25rem;">
                    @php $pct = min(100, ($event->attendees_count / $event->max_attendees) * 100); @endphp
                    <div style="display: flex; justify-content: space-between; font-size: 0.82rem; color: #64748b; margin-bottom: 0.4rem;">
                        <span><i class="fas fa-users"></i> {{ $event->attendees_count }} registered</span>
                        <span>{{ $event->spots_left }} spots left</span>
                    </div>
                    <div class="spots-bar">
                        <div class="spots-fill {{ $pct >= 90 ? 'almost-full' : '' }} {{ $event->is_full ? 'full' : '' }}"
                             style="width: {{ $pct }}%"></div>
                    </div>
                </div>
                @endif

                {{-- REGISTER BUTTON --}}
                @auth
                    @if($isRegistered)
                        <div style="background: #d1fae5; color: #065f46; padding: 1rem; border-radius: 10px; text-align: center; margin-bottom: 1rem; font-weight: 600;">
                            <i class="fas fa-check-circle"></i> You're Registered!
                        </div>
                        <form method="POST" action="{{ route('events.unregister', $event) }}" onsubmit="return confirm('Are you sure you want to cancel your registration?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="width: 100%; justify-content: center;">
                                <i class="fas fa-times-circle"></i> Cancel Registration
                            </button>
                        </form>
                    @elseif($event->status === 'published' && !$event->is_full && $event->start_date > now())
                        <form method="POST" action="{{ route('events.register', $event) }}">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-lg" style="width: 100%; justify-content: center;">
                                <i class="fas fa-ticket-alt"></i> Register Now
                            </button>
                        </form>
                    @elseif($event->is_full)
                        <button class="btn" style="width:100%; background:#f1f5f9; color:#94a3b8; cursor:not-allowed; justify-content: center;" disabled>
                            <i class="fas fa-ban"></i> Event Full
                        </button>
                    @elseif($event->start_date <= now())
                        <button class="btn" style="width:100%; background:#f1f5f9; color:#94a3b8; cursor:not-allowed; justify-content: center;" disabled>
                            <i class="fas fa-clock"></i> Event Ended
                        </button>
                    @endif

                    {{-- EDIT/DELETE for owner or admin --}}
                    @if(Auth::user()->is_admin || Auth::user()->id === $event->user_id)
                    <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #f1f5f9; display: flex; gap: 0.5rem;">
                        <a href="{{ route('events.edit', $event) }}" class="btn btn-secondary btn-sm" style="flex:1; justify-content: center;">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <button type="button" class="btn btn-danger btn-sm" onclick="confirmDeleteEvent()">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg" style="width: 100%; justify-content: center;">
                        <i class="fas fa-sign-in-alt"></i> Login to Register
                    </a>
                @endauth

                {{-- EVENT DETAILS --}}
                <div style="margin-top: 1.5rem;">
                    <div class="detail-row">
                        <div class="detail-icon"><i class="fas fa-calendar-alt"></i></div>
                        <div>
                            <div class="detail-label">Start Date</div>
                            <div class="detail-value">{{ $event->start_date->format('l, F j, Y') }}</div>
                            <div style="font-size: 0.82rem; color: #64748b;">{{ $event->start_date->format('g:i A') }}</div>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-icon"><i class="fas fa-calendar-check"></i></div>
                        <div>
                            <div class="detail-label">End Date</div>
                            <div class="detail-value">{{ $event->end_date->format('l, F j, Y') }}</div>
                            <div style="font-size: 0.82rem; color: #64748b;">{{ $event->end_date->format('g:i A') }}</div>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-icon"><i class="fas fa-map-marker-alt"></i></div>
                        <div>
                            <div class="detail-label">Location</div>
                            <div class="detail-value">{{ $event->location }}</div>
                            @if($event->venue)
                            <div style="font-size: 0.82rem; color: #64748b;">{{ $event->venue }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-icon"><i class="fas fa-tag"></i></div>
                        <div>
                            <div class="detail-label">Category</div>
                            <div class="detail-value">{{ $event->category }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.featured-badge { background: linear-gradient(90deg, #e94560, #f5a623); color: white; padding: 0.2rem 0.8rem; border-radius: 100px; font-size: 0.7rem; font-weight: 700; }
</style>
@endpush

{{-- Standalone delete form (outside any other form to avoid nesting bugs) --}}
@if(Auth::check() && (Auth::user()->is_admin || Auth::user()->id === $event->user_id))
<form method="POST" action="{{ route('events.destroy', $event) }}" id="showDeleteForm" style="display:none;">
    @csrf
    @method('DELETE')
</form>
@endif

@push('scripts')
<script>
function confirmDeleteEvent() {
    if (confirm('Delete this event permanently? This cannot be undone.')) {
        document.getElementById('showDeleteForm').submit();
    }
}
</script>
@endpush

@endsection
