<?php
// FILE: database/migrations/2024_06_01_000001_update_events_status_enum.php
//
// Run this INSTEAD of migrate:fresh so existing data is preserved.
// It alters the status column to include pending_approval & rejected,
// and adds the rejection_reason column if it doesn't exist yet.
//
// Run: php artisan migrate

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Alter the ENUM to include all statuses
        DB::statement("
            ALTER TABLE events
            MODIFY COLUMN status ENUM(
                'draft',
                'pending_approval',
                'published',
                'cancelled',
                'completed',
                'rejected'
            ) NOT NULL DEFAULT 'draft'
        ");

        // 2. Add rejection_reason column if it doesn't exist
        if (!Schema::hasColumn('events', 'rejection_reason')) {
            Schema::table('events', function (Blueprint $table) {
                $table->text('rejection_reason')->nullable()->after('status');
            });
        }
    }

    public function down(): void
    {
        // Revert ENUM to original set (drops pending_approval & rejected)
        DB::statement("
            ALTER TABLE events
            MODIFY COLUMN status ENUM(
                'draft',
                'published',
                'cancelled',
                'completed'
            ) NOT NULL DEFAULT 'draft'
        ");

        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('rejection_reason');
        });
    }
};
