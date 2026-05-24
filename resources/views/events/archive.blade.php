{{-- FILE: resources/views/events/archive.blade.php --}}
@extends('layouts.app')
@section('title', 'Event Archive')

@push('styles')
<style>
.archive-hero {
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    padding: 3rem 0 4rem;
    position: relative; overflow: hidden;
}
.archive-hero::before {
    content: '';
    position: absolute; top: -40%; right: -5%;
    width: 400px; height: 400px;
    background: radial-gradient(circle, rgba(100,116,139,.15) 0%, transparent 70%);
    border-radius: 50%;
}
.archive-hero h1 { font-size: 2.5rem; font-weight: 800; color: white; margin: 0 0 .5rem; }
.archive-hero p  { color: #94a3b8; margin: 0; font-size: 1rem; }

.arc-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 2px 20px rgba(0,0,0,.06);
    overflow: hidden;
    transition: transform .2s, box-shadow .2s;
    opacity: .92;
}
.arc-card:hover { transform: translateY(-3px); box-shadow: 0 8px 28px rgba(0,0,0,.1); opacity: 1; }

.arc-img {
    height: 180px;
    background: linear-gradient(135deg, #1e293b, #334155);
    display: flex; align-items: center; justify-content: center;
    font-size: 3rem; position: relative; overflow: hidden;
}
.arc-img img { width:100%; height:100%; object-fit:cover; position:absolute; top:0; left:0; filter: grayscale(20%); }
.completed-ribbon {
    position: absolute; top: 1rem; left: 1rem;
    background: #1e293b; color: #94a3b8;
    padding: .25rem .75rem; border-radius: 100px;
    font-size: .72rem; font-weight: 700;
    display: flex; align-items: center; gap: .3rem;
}
.arc-body { padding: 1.4rem; }
.arc-cat  { font-size: .72rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: .05em; margin-bottom: .4rem; }
.arc-title { font-size: 1rem; font-weight: 700; color: #1e293b; margin-bottom: .6rem; line-height: 1.3; }
.arc-meta { font-size: .8rem; color: #94a3b8; display: flex; align-items: center; gap: .4rem; margin-bottom: .25rem; }
.arc-footer {
    padding: 1rem 1.4rem;
    border-top: 1px solid #f1f5f9;
    display: flex; align-items: center; justify-content: space-between;
}

/* Relaunch modal */
.modal-overlay {
    display: none; position: fixed; inset: 0;
    background: rgba(0,0,0,.5); z-index: 9999;
    align-items: center; justify-content: center;
}
.modal-overlay.open { display: flex; }
.modal-box {
    background: white; border-radius: 20px;
    padding: 2rem; max-width: 480px; width: 90%;
    box-shadow: 0 20px 60px rgba(0,0,0,.3);
    animation: slideUp .25s ease;
}
@keyframes slideUp { from{ transform:translateY(20px); opacity:0; } to{ transform:translateY(0); opacity:1; } }
.modal-box h3 { margin: 0 0 .5rem; font-size: 1.2rem; }
.modal-box p  { color: #64748b; font-size: .88rem; margin: 0 0 1.5rem; }

.filter-row {
    display: flex; gap: .75rem; flex-wrap: wrap; align-items: flex-end;
    background: white; padding: 1.25rem 1.5rem;
    border-radius: 14px; box-shadow: 0 2px 20px rgba(0,0,0,.06);
    margin-bottom: 2rem;
}
.filter-row .form-control { margin: 0; }
</style>
@endpush

@section('content')
{{-- HERO --}}
<div class="archive-hero">
    <div class="container" style="position:relative;z-index:1;">
        <div style="display:flex;justify-content:space-between;align-items:flex-end;flex-wrap:wrap;gap:1rem;">
            <div>
                <p style="color:#64748b;font-size:.85rem;margin:0 0 .5rem;">
                    <i class="fas fa-archive"></i> &nbsp;Event History
                </p>
                <h1>Event Archive</h1>
                <p>Browse all completed events — their memories live on here.</p>
            </div>
            <a href="{{ route('events.index') }}" class="btn btn-outline">
                <i class="fas fa-compass"></i> Browse Live Events
            </a>
        </div>
    </div>
</div>

<div class="container section">

    {{-- FILTERS --}}
    <form method="GET" action="{{ route('events.archive') }}">
        <div class="filter-row">
            <div style="flex:1;min-width:200px;">
                <input type="text" name="search" class="form-control" placeholder="Search archived events..."
                       value="{{ request('search') }}">
            </div>
            <div>
                <select name="category" class="form-control" style="margin:0;">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filter</button>
            @if(request()->hasAny(['search','category']))
                <a href="{{ route('events.archive') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Clear</a>
            @endif
        </div>
    </form>

    {{-- STATS BAR --}}
    <div style="background:white;border-radius:14px;padding:1.25rem 1.75rem;box-shadow:0 2px 10px rgba(0,0,0,.05);margin-bottom:2rem;display:flex;gap:2rem;flex-wrap:wrap;align-items:center;">
        <div style="display:flex;align-items:center;gap:.5rem;font-size:.88rem;color:#64748b;">
            <i class="fas fa-check-circle" style="color:#10b981;"></i>
            <strong style="color:#1e293b;">{{ $events->total() }}</strong> completed events
        </div>
        @if(Auth::check() && Auth::user()->is_admin)
        <div style="margin-left:auto;">
            <span style="font-size:.82rem;color:#94a3b8;"><i class="fas fa-info-circle"></i> Click "Relaunch" to reschedule a past event</span>
        </div>
        @endif
    </div>

    {{-- EVENT GRID --}}
    @if($events->count())
    <div class="grid-3">
        @foreach($events as $event)
        @php $icons = ['Technology'=>'💡','Business'=>'💼','Design'=>'🎨','Health'=>'🏃','Entertainment'=>'🎵','Education'=>'📚','Sports'=>'🏆']; @endphp
        <div class="arc-card">
            <div class="arc-img">
                @if($event->image_url)
                    <img src="{{ $event->image_url }}" alt="{{ $event->title }}">
                @else
                    {{ $icons[$event->category] ?? '📅' }}
                @endif
                <div class="completed-ribbon"><i class="fas fa-flag-checkered"></i> Completed</div>
            </div>
            <div class="arc-body">
                <div class="arc-cat">{{ $event->category }}</div>
                <h3 class="arc-title">{{ Str::limit($event->title, 55) }}</h3>
                <div class="arc-meta"><i class="fas fa-calendar-check"></i> {{ $event->start_date->format('M d, Y') }} – {{ $event->end_date->format('M d, Y') }}</div>
                <div class="arc-meta"><i class="fas fa-map-marker-alt"></i> {{ $event->location }}</div>
                <div class="arc-meta"><i class="fas fa-users"></i> {{ $event->attendees_count }} attended</div>
            </div>
            <div class="arc-footer">
                <a href="{{ route('events.show', $event) }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-eye"></i> View
                </a>
                @if(Auth::check() && Auth::user()->is_admin)
                <button type="button" class="btn btn-primary btn-sm"
                        onclick="openRelaunch({{ $event->id }}, '{{ addslashes($event->title) }}')">
                    <i class="fas fa-redo"></i> Relaunch
                </button>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <div style="margin-top:3rem;display:flex;justify-content:center;">
        {{ $events->withQueryString()->links() }}
    </div>

    @else
    <div style="text-align:center;padding:5rem 2rem;color:#94a3b8;">
        <i class="fas fa-archive" style="font-size:4rem;margin-bottom:1rem;display:block;"></i>
        <h3 style="color:#475569;font-size:1.5rem;">No Archived Events</h3>
        <p>Completed events will appear here.</p>
        <a href="{{ route('events.index') }}" class="btn btn-primary" style="margin-top:1rem;">
            <i class="fas fa-compass"></i> Browse Live Events
        </a>
    </div>
    @endif
</div>

{{-- RELAUNCH MODAL (admin only) --}}
@if(Auth::check() && Auth::user()->is_admin)
<div class="modal-overlay" id="relaunchModal">
    <div class="modal-box">
        <h3><i class="fas fa-redo" style="color:#e94560;"></i> Relaunch Event</h3>
        <p id="relaunchEventName" style="font-weight:600;color:#1e293b;margin-bottom:.5rem;"></p>
        <p>Set new dates for this event. It will be republished as a live event immediately.</p>
        <form method="POST" id="relaunchForm">
            @csrf
            <div class="form-group">
                <label class="form-label">New Start Date & Time *</label>
                <input type="datetime-local" name="start_date" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">New End Date & Time *</label>
                <input type="datetime-local" name="end_date" class="form-control" required>
            </div>
            <div style="display:flex;gap:.75rem;justify-content:flex-end;margin-top:1.5rem;">
                <button type="button" class="btn btn-secondary btn-lg" onclick="closeRelaunch()">
                    Cancel
                </button>
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-rocket"></i> Relaunch Event
                </button>
            </div>
        </form>
    </div>
</div>
@endif

@push('scripts')
<script>
function openRelaunch(id, title) {
    document.getElementById('relaunchEventName').textContent = title;
    document.getElementById('relaunchForm').action = '/events/' + id + '/relaunch';
    document.getElementById('relaunchModal').classList.add('open');
}
function closeRelaunch() {
    document.getElementById('relaunchModal').classList.remove('open');
}
// Close on backdrop click
document.getElementById('relaunchModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeRelaunch();
});
</script>
@endpush
@endsection
