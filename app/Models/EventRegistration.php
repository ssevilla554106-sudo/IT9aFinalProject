<?php
// FILE: app/Models/EventRegistration.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EventRegistration extends Model
{
    protected $fillable = ['user_id', 'event_id', 'status', 'ticket_number', 'notes'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($registration) {
            $registration->ticket_number = 'TKT-' . strtoupper(Str::random(8));
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}