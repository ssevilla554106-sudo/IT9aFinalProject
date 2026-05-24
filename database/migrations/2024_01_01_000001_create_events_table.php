<?php
// FILE: database/migrations/2024_01_01_000001_create_events_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('location');
            $table->string('venue')->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->string('category');
            // pending_approval = submitted by user, awaiting admin review
            // draft            = saved by creator, not yet submitted
            // published        = approved & live
            // cancelled        = cancelled
            // completed        = event has ended (archived)
            // rejected         = rejected by admin
            $table->enum('status', [
                'draft',
                'pending_approval',
                'published',
                'cancelled',
                'completed',
                'rejected',
            ])->default('draft');
            $table->text('rejection_reason')->nullable(); // admin's rejection note
            $table->integer('max_attendees')->nullable();
            $table->decimal('ticket_price', 10, 2)->default(0);
            $table->string('image')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
