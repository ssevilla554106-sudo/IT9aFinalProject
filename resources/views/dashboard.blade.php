{{-- FILE: resources/views/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard')

@push('styles')
<style>
.welcome-banner {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 60%, #0f3460 100%);
    color: white;
    border-radius: 20px;
    padding: 2.5rem;
    margin-bottom: 2rem;
    position: relative;
    overflow: hidden;
}
.welcome-banner::before {
    content: '';
    position: absolute;
    top: -40%;
    right: -5%;
    width: 350px; height: 350px;
    background: radial-gradient(circle, rgba(233,69,96,0.18) 0%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
}
.welcome-banner h2 { font-size: 1.9rem; font-weight: 800; margin: 0 0 0.4rem; }
.welcome-banner p  { color: #94a3b8; margin: 0; }

.stat-card {
    background: white;
    border-radius: 16px;
    padding: 1.6rem 1.75rem;
    box-shadow: 0 2px 20px rgba(0,0,0,0.06);
    display: flex;
    align-items: center;
    gap: 1.25rem;
    transition: transform .2s, box-shadow .2s;
}
.stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 28px rgba(0,0,0,0.1); }
.stat-icon {
    width: 56px; height: 56px;
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem; flex-shrink: 0;
}
.stat-value { font-size: 1.85rem; font-weight: 800; font-family: 'Syne', sans-serif; line-height: 1; }
.stat-label { font-size: 0.82rem; color: #64748b; font-weight: 500; margin-top: 0.2rem; }

.panel {
    background: white;
    border-radius: 20px;
    box-shadow: 0 2px 20px rgba(0,0,0,0.06);
    overflow: hidden;
}
.panel-header {
    padding: 1.35rem 1.6rem;
    border-bottom: 1px solid #f1f5f9;
    display: flex; justify-content: space-between; align-items: center;
}
.panel-header h3 { margin: 0; font-size: 1.05rem; font-weight: 700; }
.panel-body { padding: 1.25rem 1.6rem; }

.event-row-dash {
    display: flex; align-items: center; gap: 1rem;
    padding: 0.85rem 0; border-bottom: 1px solid #f8fafc;
}
.event-row-dash:last-child { border-bottom: none; }
.ev-icon {
    width: 42px; height: 42px; border-radius: 10px;
    background: linear-gradient(135deg, #1a1a2e, #0f3460);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem; flex-shrink: 0;
}
.ev-title { font-weight: 600; font-size: 0.9rem; color: #1e293b; }
.ev-meta  { font-size: 0.78rem; color: #94a3b8; margin-top: 0.15rem; }

.cal-chip {
    width: 46px; height: 46px; border-radius: 10px;
    background: linear-gradient(135deg, #e94560, #0f3460);
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    color: white; flex-shrink: 0;
}
.cal-chip .mon { font-size: 0.6rem; text-transform: uppercase; letter-spacing: .04em; }
.cal-chip .day { font-size: 1.05rem; font-weight: 800; line-height: 1; }

.cat-bar-wrap { margin-bottom: 0.85rem; }
.cat-bar-label { display: flex; justify-content: space-between; font-size: 0.82rem; font-weight: 600; color: #374151; margin-bottom: 0.3rem; }
.cat-bar-track { background: #f1f5f9; border-radius: 100px; height: 8px; overflow: hidden; }
.cat-bar-fill  { height: 100%; border-radius: 100px; background: linear-gradient(90deg, #e94560, #f5a623); }

.ticket-row {
    display: flex; align-items: center; gap: 1rem;
    padding: 0.9rem 1.25rem; border-radius: 12px;
    background: linear-gradient(135deg, #1a1a2e, #16213e);
    color: white; margin-bottom: 0.75rem;
}
.ticket-code {
    font-family: 'Courier New', monospace;
    background: rgba(255,255,255,0.1);
    padding: 0.2rem 0.6rem; border-radius: 5px;
    font-size: 0.75rem; letter-spacing: .04em; white-space: nowrap;
}

.summary-grid {
    display: grid; grid-template-columns: repeat(3,1fr); gap: 1rem;
}
@media(max-width:700px){ .summary-grid{ grid-template-columns:1fr 1fr; } }
.summary-chip {
    background: #f8fafc; border-radius: 14px; padding: 1.2rem 1.25rem;
    text-align: center; border: 1.5px solid #f1f5f9;
}
.summary-chip .num { font-size: 1.6rem; font-weight: 800; font-family:'Syne',sans-serif; color:#1e293b; }
.summary-chip .lbl { font-size: 0.78rem; color: #64748b; font-weight: 500; margin-top: 0.2rem; }

.empty-panel { text-align:center; padding:2.5rem 1rem; color:#94a3b8; }
.empty-panel i { font-size:2.2rem; margin-bottom:.75rem; display:block; }
.empty-panel p { margin: 0 0 1rem; }
</style>
@endpush

@section('content')
<div class="container section">

{{-- ══════════════════════════════════════════════════
     WELCOME BANNER
══════════════════════════════════════════════════ --}}
<div class="welcome-banner">
    <div style="position:relative;z-index:1;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;">
        <div>
            <p style="color:#e94560;font-weight:700;font-size:0.8rem;text-transform:uppercase;letter-spacing:.06em;margin:0 0 .4rem;">
                @if(Auth::user()->is_admin) ⚡ Admin Panel @else 👋 Welcome Back @endif
            </p>
            <h2>{{ Auth::user()->name }}</h2>
            <p>
                @if(Auth::user()->is_admin)
                    Full control of all events, users, and platform activity.
                @else
                    Here's your personal event hub — create, manage, and discover events.
                @endif
            </p>
        </div>
        <div style="display:flex;gap:.75rem;flex-wrap:wrap;">
            <a href="{{ route('events.create') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-plus"></i> Create Event
            </a>
            <a href="{{ route('events.index') }}" class="btn btn-outline btn-lg">
                <i class="fas fa-compass"></i> Browse
            </a>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════
     ADMIN DASHBOARD
══════════════════════════════════════════════════ --}}
@if(Auth::user()->is_admin)

{{-- ADMIN STAT CARDS --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1.25rem;margin-bottom:2rem;">
    <div class="stat-card">
        <div class="stat-icon" style="background:#fff0f3;color:#e94560;"><i class="fas fa-calendar-alt"></i></div>
        <div><div class="stat-value">{{ $totalEvents }}</div><div class="stat-label">Total Events</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#d1fae5;color:#10b981;"><i class="fas fa-check-circle"></i></div>
        <div><div class="stat-value">{{ $publishedEvents }}</div><div class="stat-label">Published</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#dbeafe;color:#3b82f6;"><i class="fas fa-ticket-alt"></i></div>
        <div><div class="stat-value">{{ $totalRegistrations }}</div><div class="stat-label">Registrations</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fef3c7;color:#f59e0b;"><i class="fas fa-users"></i></div>
        <div><div class="stat-value">{{ $totalUsers }}</div><div class="stat-label">Users</div></div>
    </div>
</div>

{{-- SECONDARY ADMIN STATS --}}
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1.25rem;margin-bottom:2rem;">
    <div class="stat-card">
        <div class="stat-icon" style="background:#f1f5f9;color:#64748b;"><i class="fas fa-file-alt"></i></div>
        <div><div class="stat-value">{{ $draftEvents }}</div><div class="stat-label">Draft Events</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fee2e2;color:#ef4444;"><i class="fas fa-ban"></i></div>
        <div><div class="stat-value">{{ $cancelledEvents }}</div><div class="stat-label">Cancelled</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#ede9fe;color:#8b5cf6;"><i class="fas fa-calendar-check"></i></div>
        <div><div class="stat-value">{{ $totalUpcomingEvents }}</div><div class="stat-label">Upcoming</div></div>
    </div>
</div>


    {{-- ══ PENDING APPROVALS (admin) ══ --}}
    @if(Auth::user()->is_admin && $pendingApprovals->count())
    <div class="panel" style="margin-bottom:2rem;border:2px solid #fef3c7;">
        <div class="panel-header" style="background:#fffbeb;">
            <h3 style="color:#92400e;">
                <i class="fas fa-clock" style="color:#f59e0b;"></i>
                Pending Approval
                <span style="background:#f59e0b;color:white;border-radius:100px;font-size:.72rem;padding:.2rem .65rem;margin-left:.5rem;">{{ $pendingApprovals->count() }}</span>
            </h3>
            <span style="font-size:.82rem;color:#92400e;">Events waiting for your review</span>
        </div>
        <div>
            @foreach($pendingApprovals as $event)
            <div style="display:flex;align-items:center;gap:1rem;padding:1.1rem 1.6rem;border-bottom:1px solid #fef9c3;">
                <div class="ev-icon">
                    @php $icons=['Technology'=>'💡','Business'=>'💼','Design'=>'🎨','Health'=>'🏃','Entertainment'=>'🎵','Education'=>'📚','Sports'=>'🏆']; @endphp
                    {{ $icons[$event->category] ?? '📅' }}
                </div>
                <div style="flex:1;min-width:0;">
                    <div class="ev-title" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $event->title }}</div>
                    <div class="ev-meta">
                        By <strong>{{ $event->user->name }}</strong> ·
                        {{ $event->start_date->format('M d, Y') }} ·
                        {{ $event->category }}
                    </div>
                </div>
                <div style="display:flex;gap:.5rem;flex-shrink:0;">
                    <a href="{{ route('events.show', $event) }}" class="btn btn-secondary btn-sm" title="Preview">
                        <i class="fas fa-eye"></i>
                    </a>
                    <form method="POST" action="{{ route('events.approve', $event) }}" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm" title="Approve">
                            <i class="fas fa-check"></i> Approve
                        </button>
                    </form>
                    <button type="button" class="btn btn-danger btn-sm"
                            onclick="openRejectModal({{ $event->id }}, '{{ addslashes($event->title) }}')"
                            title="Reject">
                        <i class="fas fa-times"></i> Reject
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

<div style="display:grid;grid-template-columns:1fr 380px;gap:1.5rem;align-items:start;margin-bottom:2rem;">

    {{-- RECENT EVENTS TABLE --}}
    <div class="panel">
        <div class="panel-header">
            <h3><i class="fas fa-history" style="color:#e94560;"></i> Recent Events</h3>
            <a href="{{ route('events.index') }}" class="btn btn-secondary btn-sm">View All</a>
        </div>
        <div>
            @forelse($recentEvents as $event)
            <div style="display:flex;align-items:center;gap:1rem;padding:1rem 1.6rem;border-bottom:1px solid #f8fafc;">
                <div class="ev-icon">
                    @php $icons=['Technology'=>'💡','Business'=>'💼','Design'=>'🎨','Health'=>'🏃','Entertainment'=>'🎵','Education'=>'📚','Sports'=>'🏆']; @endphp
                    {{ $icons[$event->category] ?? '📅' }}
                </div>
                <div style="flex:1;min-width:0;">
                    <div class="ev-title" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $event->title }}</div>
                    <div class="ev-meta">
                        {{ $event->start_date->format('M d, Y') }} ·
                        {{ $event->attendees_count }} registered ·
                        <span class="badge badge-{{ $event->status }}" style="font-size:.7rem;padding:.15rem .5rem;">{{ ucfirst($event->status) }}</span>
                    </div>
                </div>
                <div style="display:flex;gap:.4rem;flex-shrink:0;">
                    <a href="{{ route('events.show',$event) }}" class="btn btn-secondary btn-sm" title="View"><i class="fas fa-eye"></i></a>
                    <a href="{{ route('events.edit',$event) }}" class="btn btn-primary btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
                </div>
            </div>
            @empty
            <div class="empty-panel">
                <i class="fas fa-calendar-times"></i>
                <p>No events yet.</p>
                <a href="{{ route('events.create') }}" class="btn btn-primary btn-sm">Create First Event</a>
            </div>
            @endforelse
        </div>
    </div>

    {{-- RIGHT COLUMN --}}
    <div style="display:flex;flex-direction:column;gap:1.5rem;">

        {{-- UPCOMING EVENTS --}}
        <div class="panel">
            <div class="panel-header">
                <h3><i class="fas fa-rocket" style="color:#e94560;"></i> Upcoming</h3>
            </div>
            <div class="panel-body" style="padding-top:.75rem;padding-bottom:.75rem;">
                @forelse($upcomingEvents as $event)
                <div class="event-row-dash">
                    <div class="cal-chip">
                        <span class="mon">{{ $event->start_date->format('M') }}</span>
                        <span class="day">{{ $event->start_date->format('d') }}</span>
                    </div>
                    <div style="flex:1;min-width:0;">
                        <div class="ev-title" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $event->title }}</div>
                        <div class="ev-meta"><i class="fas fa-map-marker-alt"></i> {{ Str::limit($event->location,25) }}</div>
                    </div>
                </div>
                @empty
                <div class="empty-panel" style="padding:1.5rem;">
                    <i class="fas fa-calendar" style="font-size:1.5rem;"></i>
                    <p>No upcoming events</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- CATEGORY BREAKDOWN --}}
        <div class="panel">
            <div class="panel-header">
                <h3><i class="fas fa-chart-pie" style="color:#e94560;"></i> By Category</h3>
            </div>
            <div class="panel-body">
                @php $maxCat = $categoryStats->max('total') ?: 1; @endphp
                @forelse($categoryStats as $cat)
                <div class="cat-bar-wrap">
                    <div class="cat-bar-label">
                        <span>{{ $cat->category }}</span>
                        <span>{{ $cat->total }}</span>
                    </div>
                    <div class="cat-bar-track">
                        <div class="cat-bar-fill" style="width:{{ ($cat->total/$maxCat)*100 }}%"></div>
                    </div>
                </div>
                @empty
                <p style="color:#94a3b8;font-size:.875rem;">No data yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- QUICK ACTIONS --}}
<div class="panel" style="margin-bottom:2rem;">
    <div class="panel-header"><h3><i class="fas fa-bolt" style="color:#e94560;"></i> Quick Actions</h3></div>
    <div class="panel-body" style="display:flex;gap:1rem;flex-wrap:wrap;">
        <a href="{{ route('events.create') }}" class="btn btn-primary"><i class="fas fa-plus-circle"></i> New Event</a>
        <a href="{{ route('events.index') }}" class="btn btn-secondary"><i class="fas fa-compass"></i> Browse All Events</a>
        <a href="{{ route('events.my-events') }}" class="btn btn-secondary"><i class="fas fa-ticket-alt"></i> Manage Events</a>
        <a href="{{ route('profile.edit') }}" class="btn btn-secondary"><i class="fas fa-user-edit"></i> Edit Profile</a>
    </div>
</div>

{{-- ══════════════════════════════════════════════════
     REGULAR USER DASHBOARD
══════════════════════════════════════════════════ --}}
@else

{{-- USER STAT CARDS --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1.25rem;margin-bottom:2rem;">
    <div class="stat-card">
        <div class="stat-icon" style="background:#fff0f3;color:#e94560;"><i class="fas fa-calendar-alt"></i></div>
        <div><div class="stat-value">{{ $myEventsCount }}</div><div class="stat-label">My Events</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#dbeafe;color:#3b82f6;"><i class="fas fa-ticket-alt"></i></div>
        <div><div class="stat-value">{{ $myRegistrationsCount }}</div><div class="stat-label">Registrations</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#d1fae5;color:#10b981;"><i class="fas fa-calendar-check"></i></div>
        <div><div class="stat-value">{{ $myUpcomingCount }}</div><div class="stat-label">Upcoming</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#f1f5f9;color:#64748b;"><i class="fas fa-file-alt"></i></div>
        <div><div class="stat-value">{{ $myDraftCount }}</div><div class="stat-label">Drafts</div></div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 380px;gap:1.5rem;align-items:start;margin-bottom:2rem;">

    {{-- MY CREATED EVENTS --}}
    <div class="panel">
        <div class="panel-header">
            <h3><i class="fas fa-calendar-plus" style="color:#e94560;"></i> My Created Events</h3>
            <a href="{{ route('events.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> New</a>
        </div>
        <div>
            @forelse($myRecentEvents as $event)
            <div style="display:flex;align-items:center;gap:1rem;padding:1rem 1.6rem;border-bottom:1px solid #f8fafc;">
                <div class="ev-icon">
                    @php $icons=['Technology'=>'💡','Business'=>'💼','Design'=>'🎨','Health'=>'🏃','Entertainment'=>'🎵','Education'=>'📚','Sports'=>'🏆']; @endphp
                    {{ $icons[$event->category] ?? '📅' }}
                </div>
                <div style="flex:1;min-width:0;">
                    <div class="ev-title" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $event->title }}</div>
                    <div class="ev-meta">
                        {{ $event->start_date->format('M d, Y') }} ·
                        {{ $event->attendees_count }} registered ·
                        <span class="badge badge-{{ $event->status }}" style="font-size:.7rem;padding:.15rem .5rem;">{{ ucfirst($event->status) }}</span>
                    </div>
                </div>
                <div style="display:flex;gap:.4rem;flex-shrink:0;">
                    <a href="{{ route('events.show',$event) }}" class="btn btn-secondary btn-sm"><i class="fas fa-eye"></i></a>
                    <a href="{{ route('events.edit',$event) }}" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                </div>
            </div>
            @empty
            <div class="empty-panel">
                <i class="fas fa-calendar-plus"></i>
                <p>You haven't created any events yet.</p>
                <a href="{{ route('events.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Create Event</a>
            </div>
            @endforelse
        </div>
        @if($myRecentEvents->count())
        <div style="padding:1rem 1.6rem;border-top:1px solid #f1f5f9;text-align:right;">
            <a href="{{ route('events.my-events') }}" class="btn btn-secondary btn-sm">View All My Events →</a>
        </div>
        @endif
    </div>

    {{-- RIGHT COLUMN --}}
    <div style="display:flex;flex-direction:column;gap:1.5rem;">

        {{-- UPCOMING REGISTRATIONS --}}
        <div class="panel">
            <div class="panel-header">
                <h3><i class="fas fa-ticket-alt" style="color:#e94560;"></i> My Tickets</h3>
                <a href="{{ route('events.my-events') }}" class="btn btn-secondary btn-sm">All</a>
            </div>
            <div class="panel-body" style="padding-top:.5rem;padding-bottom:.5rem;">
                @forelse($upcomingRegistrations as $event)
                <div class="ticket-row">
                    <div class="cal-chip">
                        <span class="mon">{{ $event->start_date->format('M') }}</span>
                        <span class="day">{{ $event->start_date->format('d') }}</span>
                    </div>
                    <div style="flex:1;min-width:0;">
                        <div style="font-weight:600;font-size:.88rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $event->title }}</div>
                        <div style="font-size:.75rem;color:#94a3b8;margin-top:.15rem;">
                            <span class="ticket-code">{{ $event->pivot->ticket_number }}</span>
                        </div>
                    </div>
                    <a href="{{ route('events.show',$event) }}" class="btn btn-outline btn-sm" style="flex-shrink:0;">View</a>
                </div>
                @empty
                <div class="empty-panel" style="padding:1.5rem;">
                    <i class="fas fa-ticket-alt" style="font-size:1.8rem;"></i>
                    <p>No upcoming registered events.</p>
                    <a href="{{ route('events.index') }}" class="btn btn-primary btn-sm">Browse Events</a>
                </div>
                @endforelse
            </div>
        </div>

        {{-- MY CATEGORY BREAKDOWN --}}
        @if($myCategoryStats->count())
        <div class="panel">
            <div class="panel-header">
                <h3><i class="fas fa-chart-pie" style="color:#e94560;"></i> My Categories</h3>
            </div>
            <div class="panel-body">
                @php $maxCat = $myCategoryStats->max('total') ?: 1; @endphp
                @foreach($myCategoryStats as $cat)
                <div class="cat-bar-wrap">
                    <div class="cat-bar-label">
                        <span>{{ $cat->category }}</span><span>{{ $cat->total }}</span>
                    </div>
                    <div class="cat-bar-track">
                        <div class="cat-bar-fill" style="width:{{ ($cat->total/$maxCat)*100 }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

{{-- QUICK ACTIONS --}}
<div class="panel" style="margin-bottom:2rem;">
    <div class="panel-header"><h3><i class="fas fa-bolt" style="color:#e94560;"></i> Quick Actions</h3></div>
    <div class="panel-body" style="display:flex;gap:1rem;flex-wrap:wrap;">
        <a href="{{ route('events.create') }}" class="btn btn-primary"><i class="fas fa-plus-circle"></i> Create Event</a>
        <a href="{{ route('events.index') }}" class="btn btn-secondary"><i class="fas fa-compass"></i> Browse Events</a>
        <a href="{{ route('events.my-events') }}" class="btn btn-secondary"><i class="fas fa-ticket-alt"></i> My Events & Tickets</a>
        <a href="{{ route('events.archive') }}" class="btn btn-secondary"><i class="fas fa-archive"></i> Archive</a>
        <a href="{{ route('profile.edit') }}" class="btn btn-secondary"><i class="fas fa-user-edit"></i> Edit Profile</a>
    </div>
</div>
@endif

{{-- ══════════════════════════════════════════════════
     PLATFORM SUMMARY (visible to ALL users)
══════════════════════════════════════════════════ --}}
<div class="panel" style="margin-bottom:2rem;">
    <div class="panel-header">
        <h3><i class="fas fa-globe" style="color:#e94560;"></i> Platform Summary</h3>
        <a href="{{ route('events.index') }}" class="btn btn-secondary btn-sm">Browse All</a>
    </div>
    <div class="panel-body">
        <div class="summary-grid" style="margin-bottom:1.5rem;">
            <div class="summary-chip">
                <div class="num">{{ $totalPublishedEvents }}</div>
                <div class="lbl">Published Events</div>
            </div>
            <div class="summary-chip">
                <div class="num">{{ $totalUpcomingEvents }}</div>
                <div class="lbl">Upcoming Events</div>
            </div>
            <div class="summary-chip">
                <div class="num">{{ count(\App\Models\Event::getCategories()) }}</div>
                <div class="lbl">Categories</div>
            </div>
        </div>

        @if($recentPublicEvents->count())
        <h4 style="font-size:.9rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.05em;margin:0 0 1rem;">
            Upcoming Events on Platform
        </h4>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;">
            @foreach($recentPublicEvents->take(6) as $event)
            <a href="{{ route('events.show',$event) }}" style="text-decoration:none;">
                <div style="background:#f8fafc;border-radius:12px;padding:1rem;transition:background .2s;border:1.5px solid #f1f5f9;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#f8fafc'">
                    <div style="font-size:.72rem;font-weight:700;color:#e94560;text-transform:uppercase;letter-spacing:.04em;margin-bottom:.3rem;">{{ $event->category }}</div>
                    <div style="font-weight:700;color:#1e293b;font-size:.9rem;margin-bottom:.4rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $event->title }}</div>
                    <div style="font-size:.78rem;color:#64748b;">
                        <i class="fas fa-calendar"></i> {{ $event->start_date->format('M d, Y') }}<br>
                        <i class="fas fa-map-marker-alt"></i> {{ Str::limit($event->location,22) }}
                    </div>
                    <div style="margin-top:.6rem;font-weight:700;font-size:.85rem;color:{{ $event->ticket_price==0 ? '#10b981' : '#1e293b' }};">
                        {{ $event->ticket_price==0 ? 'Free' : '$'.number_format($event->ticket_price,2) }}
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        @endif
    </div>
</div>


{{-- REJECT MODAL --}}
@if(Auth::check() && Auth::user()->is_admin)
<div id="rejectModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:9999;align-items:center;justify-content:center;">
    <div style="background:white;border-radius:20px;padding:2rem;max-width:460px;width:90%;box-shadow:0 20px 60px rgba(0,0,0,.3);">
        <h3 style="margin:0 0 .4rem;"><i class="fas fa-times-circle" style="color:#ef4444;"></i> Reject Event</h3>
        <p id="rejectEventName" style="font-weight:600;color:#1e293b;margin-bottom:.5rem;font-size:.95rem;"></p>
        <p style="color:#64748b;font-size:.85rem;margin-bottom:1.25rem;">Please provide a reason so the organiser can improve their submission.</p>
        <form method="POST" id="rejectForm">
            @csrf
            <div class="form-group">
                <label class="form-label" style="color:#dc2626;">Rejection Reason *</label>
                <textarea name="rejection_reason" class="form-control" rows="3"
                          placeholder="e.g. Missing venue details, incomplete description..." required></textarea>
            </div>
            <div style="display:flex;gap:.75rem;justify-content:flex-end;margin-top:1.25rem;">
                <button type="button" class="btn btn-secondary btn-lg" onclick="closeRejectModal()">Cancel</button>
                <button type="submit" class="btn btn-danger btn-lg"><i class="fas fa-times"></i> Reject Event</button>
            </div>
        </form>
    </div>
</div>
@endif

</div>{{-- end container --}}
@endsection

@push('scripts')
<script>
function openRejectModal(id, title) {
    document.getElementById('rejectEventName').textContent = title;
    document.getElementById('rejectForm').action = '/events/' + id + '/reject';
    const modal = document.getElementById('rejectModal');
    modal.style.display = 'flex';
}
function closeRejectModal() {
    document.getElementById('rejectModal').style.display = 'none';
}
document.getElementById('rejectModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeRejectModal();
});
</script>
@endpush

