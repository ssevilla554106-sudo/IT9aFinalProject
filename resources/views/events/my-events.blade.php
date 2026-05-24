{{-- FILE: resources/views/events/my-events.blade.php --}}
@extends('layouts.app')

@section('title', 'My Events')

@push('styles')
<style>
.tab-nav { display: flex; gap: 0.5rem; margin-bottom: 2rem; background: white; padding: 0.4rem; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.06); width: fit-content; }
.tab-btn { padding: 0.65rem 1.5rem; border-radius: 8px; font-weight: 600; cursor: pointer; border: none; background: none; font-family: 'DM Sans', sans-serif; font-size: 0.9rem; color: #64748b; transition: all 0.2s; }
.tab-btn.active { background: #e94560; color: white; }
.event-row { display: flex; align-items: center; gap: 1rem; padding: 1.25rem; background: white; border-radius: 14px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 1rem; transition: box-shadow 0.2s; }
.event-row:hover { box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
.event-row-icon { width: 56px; height: 56px; border-radius: 12px; background: linear-gradient(135deg, #1a1a2e, #0f3460); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0; }
.event-row-info { flex: 1; min-width: 0; }
.event-row-title { font-weight: 700; color: #1e293b; font-size: 1rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.event-row-meta { font-size: 0.82rem; color: #64748b; margin-top: 0.25rem; display: flex; gap: 1rem; flex-wrap: wrap; }
.event-row-actions { display: flex; gap: 0.5rem; flex-shrink: 0; }
.status-pending  { background:#fef3c7;color:#92400e;border-radius:8px;padding:.35rem .75rem;font-size:.78rem;font-weight:600;display:inline-flex;align-items:center;gap:.3rem; }
.status-rejected { background:#fee2e2;color:#991b1b;border-radius:8px;padding:.35rem .75rem;font-size:.78rem;font-weight:600;display:inline-flex;align-items:center;gap:.3rem; }
.rejection-box   { background:#fff5f5;border:1.5px solid #fee2e2;border-radius:10px;padding:.75rem 1rem;font-size:.82rem;color:#991b1b;margin-top:.5rem;display:flex;gap:.5rem;align-items:flex-start; }
.ticket-box { background: linear-gradient(135deg, #1a1a2e, #0f3460); color: white; border-radius: 14px; padding: 1.25rem 1.5rem; display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem; }
.ticket-number { font-family: 'Courier New', monospace; background: rgba(255,255,255,0.1); padding: 0.3rem 0.8rem; border-radius: 6px; font-size: 0.85rem; letter-spacing: 0.05em; }
</style>
@endpush

@section('content')
<div class="page-header">
    <div class="container">
        <h1><i class="fas fa-ticket-alt" style="color: #e94560;"></i> My Events</h1>
        <p>Manage your created events and view your registrations</p>
    </div>
</div>

<div class="container" style="padding-top: 2.5rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <div class="tab-nav">
            <button class="tab-btn active" onclick="showTab('created', this)">
                <i class="fas fa-calendar-plus"></i> Created ({{ $createdEvents->count() }})
            </button>
            <button class="tab-btn" onclick="showTab('registered', this)">
                <i class="fas fa-ticket-alt"></i> Registered ({{ $registeredEvents->count() }})
            </button>
        </div>
        <a href="{{ route('events.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> New Event
        </a>
    </div>

    {{-- CREATED EVENTS TAB --}}
    <div id="tab-created">
        @if($createdEvents->count())
            @foreach($createdEvents as $event)
            <div class="event-row">
                <div class="event-row-icon">
                    @php $icons = ['Technology' => '💡', 'Business' => '💼', 'Design' => '🎨', 'Health' => '🏃', 'Entertainment' => '🎵', 'Education' => '📚', 'Sports' => '🏆']; @endphp
                    {{ $icons[$event->category] ?? '📅' }}
                </div>
                <div class="event-row-info">
                    <div class="event-row-title">{{ $event->title }}</div>
                    <div class="event-row-meta">
                        <span><i class="fas fa-calendar"></i> {{ $event->start_date->format('M d, Y') }}</span>
                        <span><i class="fas fa-map-marker-alt"></i> {{ $event->location }}</span>
                        <span><i class="fas fa-users"></i> {{ $event->attendees_count }} registered</span>
                        @if($event->status === 'pending_approval')
                            <span class="status-pending"><i class="fas fa-clock"></i> Pending Approval</span>
                        @elseif($event->status === 'rejected')
                            <span class="status-rejected"><i class="fas fa-times-circle"></i> Rejected</span>
                        @else
                            <span class="badge badge-{{ $event->status }}">{{ ucfirst($event->status) }}</span>
                        @endif
                    </div>
                    @if($event->status === 'rejected' && $event->rejection_reason)
                    <div class="rejection-box">
                        <i class="fas fa-exclamation-circle" style="flex-shrink:0;margin-top:.1rem;"></i>
                        <div><strong>Rejection reason:</strong> {{ $event->rejection_reason }}
                        — <a href="{{ route('events.edit', $event) }}" style="color:#e94560;font-weight:600;">Edit & Resubmit</a></div>
                    </div>
                    @endif
                </div>
                <div class="event-row-actions">
                    <a href="{{ route('events.show', $event) }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-eye"></i>
                    </a>
                    @if($event->status !== 'pending_approval')
                    <a href="{{ route('events.edit', $event) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-edit"></i>
                    </a>
                    @endif
                        <button type="button" class="btn btn-danger btn-sm"
                                onclick="deleteEvent({{ $event->id }})">
                            <i class="fas fa-trash"></i>
                        </button>
                </div>
            </div>
            @endforeach
        @else
            <div style="text-align: center; padding: 4rem; color: #94a3b8;">
                <i class="fas fa-calendar-plus" style="font-size: 3rem; margin-bottom: 1rem; display: block;"></i>
                <h3 style="color: #475569;">No events created yet</h3>
                <p>Create your first event to get started!</p>
                <a href="{{ route('events.create') }}" class="btn btn-primary" style="margin-top: 1rem;">
                    <i class="fas fa-plus"></i> Create Event
                </a>
            </div>
        @endif
    </div>

    {{-- REGISTERED EVENTS TAB --}}
    <div id="tab-registered" style="display: none;">
        @if($registeredEvents->count())
            @foreach($registeredEvents as $event)
            <div class="ticket-box">
                <div style="flex: 1;">
                    <div style="font-size: 0.75rem; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.3rem;">{{ $event->category }}</div>
                    <div style="font-weight: 700; font-size: 1.1rem; margin-bottom: 0.5rem;">{{ $event->title }}</div>
                    <div style="font-size: 0.85rem; color: #94a3b8; display: flex; gap: 1.5rem; flex-wrap: wrap;">
                        <span><i class="fas fa-calendar"></i> {{ $event->start_date->format('M d, Y') }}</span>
                        <span><i class="fas fa-map-marker-alt"></i> {{ $event->location }}</span>
                        <span class="ticket-number">{{ $event->pivot->ticket_number }}</span>
                    </div>
                </div>
                <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 0.5rem; flex-shrink: 0; margin-left: 1rem;">
                    <a href="{{ route('events.show', $event) }}" class="btn btn-outline btn-sm">View</a>
                    @if($event->start_date > now())
                    <form method="POST" action="{{ route('events.unregister', $event) }}" onsubmit="return confirm('Cancel registration?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm" style="background: rgba(239,68,68,0.2); color: #fca5a5; border: 1px solid rgba(239,68,68,0.3);">
                            Cancel
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            @endforeach
        @else
            <div style="text-align: center; padding: 4rem; color: #94a3b8;">
                <i class="fas fa-ticket-alt" style="font-size: 3rem; margin-bottom: 1rem; display: block;"></i>
                <h3 style="color: #475569;">No registrations yet</h3>
                <p>Browse events and register for ones you're interested in.</p>
                <a href="{{ route('events.index') }}" class="btn btn-primary" style="margin-top: 1rem;">
                    <i class="fas fa-compass"></i> Browse Events
                </a>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function showTab(name, btn) {
    document.querySelectorAll('[id^="tab-"]').forEach(t => t.style.display = 'none');
    document.getElementById('tab-' + name).style.display = 'block';
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
}
</script>
@endpush

{{-- Hidden delete forms rendered per event, submitted via JS --}}
@foreach($createdEvents as $event)
<form method="POST" action="{{ route('events.destroy', $event) }}"
      id="del-{{ $event->id }}" style="display:none;">
    @csrf
    @method('DELETE')
</form>
@endforeach

@push('scripts')
<script>
function deleteEvent(id) {
    if (confirm('Delete this event permanently? This cannot be undone.')) {
        document.getElementById('del-' + id).submit();
    }
}
</script>
@endpush

@endsection
