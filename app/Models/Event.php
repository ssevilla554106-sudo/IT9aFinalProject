<?php
// FILE: app/Models/Event.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'title', 'slug', 'description', 'location', 'venue',
        'start_date', 'end_date', 'category', 'status', 'max_attendees',
        'ticket_price', 'image', 'is_featured', 'rejection_reason',
    ];

    protected $casts = [
        'start_date'  => 'datetime',
        'end_date'    => 'datetime',
        'is_featured' => 'boolean',
        'ticket_price'=> 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($event) {
            if (empty($event->slug)) {
                $event->slug = Str::slug($event->title) . '-' . Str::random(5);
            }
        });
    }

    // ── Relationships ────────────────────────────────────────────────────
    public function user()        { return $this->belongsTo(User::class); }
    public function registrations(){ return $this->hasMany(EventRegistration::class); }
    public function registeredUsers()
    {
        return $this->belongsToMany(User::class, 'event_registrations')
                    ->withPivot('status', 'ticket_number')
                    ->withTimestamps();
    }

    // ── Helpers ──────────────────────────────────────────────────────────
    public function isRegisteredBy(User $user)
    {
        return $this->registrations()->where('user_id', $user->id)->exists();
    }

    // ── Computed attributes ──────────────────────────────────────────────
    public function getAttendeesCountAttribute()
    {
        return $this->registrations()->where('status', 'confirmed')->count();
    }
    public function getSpotsLeftAttribute()
    {
        if (!$this->max_attendees) return null;
        return max(0, $this->max_attendees - $this->attendees_count);
    }
    public function getIsFullAttribute()
    {
        if (!$this->max_attendees) return false;
        return $this->attendees_count >= $this->max_attendees;
    }
    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    // ── Scopes ───────────────────────────────────────────────────────────
    public function scopePublished($query)  { return $query->where('status', 'published'); }
    public function scopeFeatured($query)   { return $query->where('is_featured', true); }
    public function scopeUpcoming($query)   { return $query->where('start_date', '>=', now()); }
    public function scopeCompleted($query)  { return $query->where('status', 'completed'); }
    public function scopePending($query)    { return $query->where('status', 'pending_approval'); }

    // ── Static helpers ───────────────────────────────────────────────────
    public static function getCategories()
    {
        return ['Technology', 'Business', 'Design', 'Health', 'Entertainment', 'Education', 'Sports', 'Other'];
    }

    public static function getStatusBadgeClass($status)
    {
        return match($status) {
            'published'        => 'badge-published',
            'pending_approval' => 'badge-pending',
            'draft'            => 'badge-draft',
            'cancelled'        => 'badge-cancelled',
            'completed'        => 'badge-completed',
            'rejected'         => 'badge-rejected',
            default            => 'badge-draft',
        };
    }
}
