<?php
// FILE: app/Http/Controllers/EventController.php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EventController extends Controller
{
    use AuthorizesRequests;

    // ─── Public event listing ────────────────────────────────────────────
    public function index(Request $request)
    {
        // Only show published events that haven't ended yet
        // whereRaw keeps the comparison in MySQL to avoid timezone issues
        $query = Event::with('user')->published()->whereRaw('end_date >= NOW()');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('location', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->category) $query->where('category', $request->category);
        if ($request->date)     $query->whereDate('start_date', $request->date);
        if ($request->price === 'free') $query->where('ticket_price', 0);
        elseif ($request->price === 'paid') $query->where('ticket_price', '>', 0);

        $events        = $query->orderBy('start_date')->paginate(9);
        $categories    = Event::getCategories();
        $featuredEvents = Event::published()->featured()->whereRaw('start_date >= NOW()')->whereRaw('end_date >= NOW()')->limit(3)->get();

        return view('events.index', compact('events', 'categories', 'featuredEvents'));
    }

    // ─── Archive (completed events) ──────────────────────────────────────
    // An event is "archived" if:
    //   - its status is explicitly 'completed', OR
    //   - it was published and its end_date is in the past
    // This way events auto-appear in the archive the moment they end —
    // no scheduler or manual status change needed.
    public function archive(Request $request)
    {
        // Use DB::raw('NOW()') so the comparison happens entirely in MySQL,
        // avoiding any PHP <-> MySQL timezone mismatch.
        $query = Event::with('user')->where(function ($q) {
            $q->where('status', 'completed')
              ->orWhere(function ($q2) {
                  $q2->where('status', 'published')
                     ->whereRaw('end_date < NOW()');
              });
        });

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('location', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->category) $query->where('category', $request->category);

        $events     = $query->orderBy('end_date', 'desc')->paginate(9);
        $categories = Event::getCategories();

        return view('events.archive', compact('events', 'categories'));
    }

    // ─── Single event detail ─────────────────────────────────────────────
    public function show(Event $event)
    {
        $event->load('user', 'registrations');
        $isRegistered  = Auth::check() ? $event->isRegisteredBy(Auth::user()) : false;
        $relatedEvents = Event::published()
            ->where('category', $event->category)
            ->where('id', '!=', $event->id)
            ->limit(3)->get();

        return view('events.show', compact('event', 'isRegistered', 'relatedEvents'));
    }

    // ─── Create form ─────────────────────────────────────────────────────
    public function create()
    {
        $categories = Event::getCategories();
        return view('events.create', compact('categories'));
    }

    // ─── Store new event ─────────────────────────────────────────────────
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'required|string',
            'location'      => 'required|string|max:255',
            'venue'         => 'nullable|string|max:255',
            'start_date'    => 'required|date',
            'end_date'      => 'required|date|after:start_date',
            'category'      => 'required|string',
            'max_attendees' => 'nullable|integer|min:1',
            'ticket_price'  => 'nullable|numeric|min:0',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_featured'   => 'nullable|boolean',
            'action'        => 'required|in:draft,submit', // draft = save, submit = send for approval
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('events', 'public');
        }

        // Admin publishes directly; regular users go to pending_approval
        if ($validated['action'] === 'submit') {
            $status = Auth::user()->is_admin ? 'published' : 'pending_approval';
        } else {
            $status = 'draft';
        }

        $event = Event::create([
            'user_id'       => Auth::id(),
            'title'         => $validated['title'],
            'slug'          => Str::slug($validated['title']) . '-' . Str::random(5),
            'description'   => $validated['description'],
            'location'      => $validated['location'],
            'venue'         => $validated['venue'] ?? null,
            'start_date'    => $validated['start_date'],
            'end_date'      => $validated['end_date'],
            'category'      => $validated['category'],
            'status'        => $status,
            'max_attendees' => $validated['max_attendees'] ?? null,
            'ticket_price'  => $validated['ticket_price'] ?? 0,
            'image'         => $validated['image'] ?? null,
            'is_featured'   => $request->boolean('is_featured'),
        ]);

        $message = match($status) {
            'published'        => 'Event published successfully!',
            'pending_approval' => 'Event submitted for admin approval. It will go live once approved.',
            default            => 'Event saved as draft.',
        };

        return redirect()->route('events.show', $event)->with('success', $message);
    }

    // ─── Edit form ───────────────────────────────────────────────────────
    public function edit(Event $event)
    {
        $this->authorize('update', $event);
        $categories = Event::getCategories();
        return view('events.edit', compact('event', 'categories'));
    }

    // ─── Update event ────────────────────────────────────────────────────
    public function update(Request $request, Event $event)
    {
        $this->authorize('update', $event);

        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'required|string',
            'location'      => 'required|string|max:255',
            'venue'         => 'nullable|string|max:255',
            'start_date'    => 'required|date',
            'end_date'      => 'required|date|after:start_date',
            'category'      => 'required|string',
            'status'        => 'required|in:draft,pending_approval,published,cancelled,completed,rejected',
            'max_attendees' => 'nullable|integer|min:1',
            'ticket_price'  => 'nullable|numeric|min:0',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_featured'   => 'nullable|boolean',
        ]);

        // Non-admins can only set draft or pending_approval when editing
        if (!Auth::user()->is_admin) {
            $validated['status'] = in_array($validated['status'], ['draft', 'pending_approval'])
                ? $validated['status']
                : $event->status; // keep existing if they tried to force other statuses
        }

        if ($request->hasFile('image')) {
            if ($event->image) Storage::disk('public')->delete($event->image);
            $validated['image'] = $request->file('image')->store('events', 'public');
        }

        $validated['ticket_price'] = $validated['ticket_price'] ?? 0;
        $validated['is_featured']  = $request->boolean('is_featured');

        $event->update($validated);

        return redirect()->route('events.show', $event)->with('success', 'Event updated successfully!');
    }

    // ─── Delete event ────────────────────────────────────────────────────
    public function destroy(Event $event)
    {
        $this->authorize('delete', $event);
        if ($event->image) Storage::disk('public')->delete($event->image);
        $event->delete();

        return redirect()->route('events.index')->with('success', 'Event deleted successfully!');
    }

    // ─── Admin: Approve event ────────────────────────────────────────────
    public function approve(Event $event)
    {
        abort_unless(Auth::user()->is_admin, 403);

        $event->update([
            'status'           => 'published',
            'rejection_reason' => null,
        ]);

        return back()->with('success', '"' . $event->title . '" has been approved and published!');
    }

    // ─── Admin: Reject event ─────────────────────────────────────────────
    public function reject(Request $request, Event $event)
    {
        abort_unless(Auth::user()->is_admin, 403);

        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $event->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        return back()->with('success', '"' . $event->title . '" has been rejected.');
    }

    // ─── Admin: Relaunch completed/archived event ────────────────────────
    public function relaunch(Request $request, Event $event)
    {
        abort_unless(Auth::user()->is_admin, 403);

        // Relaunchable = explicitly completed, OR published but end_date in the past (auto-archived)
        $isArchived = $event->status === 'completed'
                   || ($event->status === 'published' && $event->end_date->isPast());

        abort_unless($isArchived, 422, 'Only archived or completed events can be relaunched.');

        $request->validate([
            'start_date' => 'required|date|after:now',
            'end_date'   => 'required|date|after:start_date',
        ]);

        $event->update([
            'status'     => 'published',
            'start_date' => $request->start_date,
            'end_date'   => $request->end_date,
            'slug'       => Str::slug($event->title) . '-' . Str::random(5),
        ]);

        return redirect()->route('events.show', $event)
            ->with('success', '"' . $event->title . '" has been relaunched and is now live!');
    }

    // ─── Register for event ──────────────────────────────────────────────
    public function register(Event $event)
    {
        if ($event->isRegisteredBy(Auth::user())) {
            return back()->with('error', 'You are already registered for this event.');
        }
        if ($event->is_full) {
            return back()->with('error', 'This event is full.');
        }

        EventRegistration::create([
            'user_id'  => Auth::id(),
            'event_id' => $event->id,
            'status'   => 'confirmed',
        ]);

        return back()->with('success', 'You have successfully registered for ' . $event->title . '!');
    }

    // ─── Unregister from event ───────────────────────────────────────────
    public function unregister(Event $event)
    {
        EventRegistration::where('user_id', Auth::id())
            ->where('event_id', $event->id)
            ->firstOrFail()
            ->delete();

        return back()->with('success', 'You have unregistered from this event.');
    }

    // ─── My events page ──────────────────────────────────────────────────
    public function myEvents()
    {
        $createdEvents    = Auth::user()->events()->with('registrations')->latest()->get();
        $registeredEvents = Auth::user()->registeredEvents()->with('registrations')->get();

        return view('events.my-events', compact('createdEvents', 'registeredEvents'));
    }

    // ─── Dashboard ───────────────────────────────────────────────────────
    public function dashboard()
    {
        $user = Auth::user();

        $totalPublishedEvents = Event::published()->count();
        $totalUpcomingEvents  = Event::published()->upcoming()->count();
        $recentPublicEvents   = Event::with('user')->published()->upcoming()->orderBy('start_date')->limit(6)->get();

        if ($user->is_admin) {
            $totalEvents         = Event::count();
            $publishedEvents     = Event::where('status', 'published')->count();
            $draftEvents         = Event::where('status', 'draft')->count();
            $cancelledEvents     = Event::where('status', 'cancelled')->count();
            $pendingEvents       = Event::where('status', 'pending_approval')->count();
            $completedEvents     = Event::where('status', 'completed')->count();
            $totalRegistrations  = EventRegistration::count();
            $totalUsers          = \App\Models\User::count();
            $recentEvents        = Event::with('user', 'registrations')->latest()->limit(8)->get();
            $upcomingEvents      = Event::published()->upcoming()->orderBy('start_date')->limit(6)->get();
            $pendingApprovals    = Event::with('user')->where('status', 'pending_approval')->latest()->get();
            $categoryStats       = Event::selectRaw('category, count(*) as total')->groupBy('category')->get();

            return view('dashboard', compact(
                'totalEvents', 'publishedEvents', 'draftEvents', 'cancelledEvents',
                'pendingEvents', 'completedEvents',
                'totalRegistrations', 'totalUsers',
                'recentEvents', 'upcomingEvents', 'pendingApprovals', 'categoryStats',
                'totalPublishedEvents', 'totalUpcomingEvents', 'recentPublicEvents'
            ));
        }

        $myEventsCount         = $user->events()->count();
        $myRegistrationsCount  = $user->registrations()->count();
        $myUpcomingCount       = $user->registeredEvents()->where('start_date', '>=', now())->count();
        $myDraftCount          = $user->events()->where('status', 'draft')->count();
        $myPendingCount        = $user->events()->where('status', 'pending_approval')->count();
        $myRejectedCount       = $user->events()->where('status', 'rejected')->count();
        $upcomingRegistrations = $user->registeredEvents()->where('start_date', '>=', now())->orderBy('start_date')->limit(5)->get();
        $myRecentEvents        = $user->events()->with('registrations')->latest()->limit(5)->get();
        $myCategoryStats       = $user->events()->selectRaw('category, count(*) as total')->groupBy('category')->get();

        return view('dashboard', compact(
            'myEventsCount', 'myRegistrationsCount', 'myUpcomingCount', 'myDraftCount',
            'myPendingCount', 'myRejectedCount',
            'upcomingRegistrations', 'myRecentEvents', 'myCategoryStats',
            'totalPublishedEvents', 'totalUpcomingEvents', 'recentPublicEvents'
        ));
    }
}
