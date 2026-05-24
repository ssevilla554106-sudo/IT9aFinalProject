<?php
// FILE: database/seeders/DatabaseSeeder.php
//
// SAFE TO RUN MULTIPLE TIMES — uses firstOrCreate for users so it
// never crashes on duplicate emails, and only seeds events if the
// events table is completely empty (won't overwrite your real data).

namespace Database\Seeders;

use App\Models\User;
use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Users (firstOrCreate = safe on re-run) ─────────────────────────
        $admin = User::firstOrCreate(
            ['email' => 'admin@events.com'],
            [
                'name'              => 'Admin User',
                'password'          => bcrypt('password'),
                'is_admin'          => true,
                'email_verified_at' => now(),
            ]
        );

        $member = User::firstOrCreate(
            ['email' => 'john@example.com'],
            [
                'name'              => 'John Doe',
                'password'          => bcrypt('password'),
                'is_admin'          => false,
                'email_verified_at' => now(),
            ]
        );

        // ── Only seed events when the table is completely empty ─────────────
        // This prevents the seeder from doubling up on re-runs while still
        // populating a fresh install with useful demo data.
        if (Event::count() > 0) {
            $this->command->info('Events table already has data — skipping event seeding.');
            return;
        }

        // ── UPCOMING / LIVE events ──────────────────────────────────────────
        $upcoming = [
            [
                'title'         => 'Tech Summit 2025',
                'description'   => 'The biggest technology conference of the year. Join industry leaders, innovators, and developers for three days of inspiring talks, workshops, and networking opportunities. Explore the latest trends in AI, cloud computing, and digital transformation.',
                'location'      => 'San Francisco, CA',
                'venue'         => 'Moscone Center',
                'start_date'    => now()->addDays(15),
                'end_date'      => now()->addDays(17),
                'category'      => 'Technology',
                'status'        => 'published',
                'max_attendees' => 500,
                'ticket_price'  => 299.00,
                'is_featured'   => true,
            ],
            [
                'title'         => 'Creative Design Workshop',
                'description'   => 'A hands-on workshop for designers and creative professionals. Learn the latest design thinking methodologies, UI/UX best practices, and tools from industry experts. Perfect for both beginners and seasoned professionals.',
                'location'      => 'New York, NY',
                'venue'         => 'Design Hub NYC',
                'start_date'    => now()->addDays(7),
                'end_date'      => now()->addDays(8),
                'category'      => 'Design',
                'status'        => 'published',
                'max_attendees' => 100,
                'ticket_price'  => 149.00,
                'is_featured'   => true,
            ],
            [
                'title'         => 'Annual Business Gala',
                'description'   => 'An elegant evening celebrating business excellence and achievements. Network with top executives, entrepreneurs, and business leaders. Includes dinner, awards ceremony, and live entertainment.',
                'location'      => 'Chicago, IL',
                'venue'         => 'The Grand Ballroom',
                'start_date'    => now()->addDays(30),
                'end_date'      => now()->addDays(31),
                'category'      => 'Business',
                'status'        => 'published',
                'max_attendees' => 300,
                'ticket_price'  => 499.00,
                'is_featured'   => false,
            ],
            [
                'title'         => 'Music & Arts Festival',
                'description'   => 'A celebration of music, art, and culture featuring local and international artists. Enjoy live performances, art installations, food vendors, and interactive experiences across multiple stages.',
                'location'      => 'Austin, TX',
                'venue'         => 'Zilker Park',
                'start_date'    => now()->addDays(45),
                'end_date'      => now()->addDays(47),
                'category'      => 'Entertainment',
                'status'        => 'published',
                'max_attendees' => 2000,
                'ticket_price'  => 75.00,
                'is_featured'   => true,
            ],
            [
                'title'         => 'Health & Wellness Expo',
                'description'   => 'Discover the latest in health, fitness, and wellness. Featuring expert talks, yoga sessions, nutrition workshops, and product demonstrations from leading health brands.',
                'location'      => 'Los Angeles, CA',
                'venue'         => 'LA Convention Center',
                'start_date'    => now()->addDays(20),
                'end_date'      => now()->addDays(21),
                'category'      => 'Health',
                'status'        => 'published',
                'max_attendees' => 800,
                'ticket_price'  => 0,
                'is_featured'   => false,
            ],
            [
                'title'         => 'Startup Pitch Night',
                'description'   => 'Watch the most promising startups pitch their ideas to investors and industry veterans. A great opportunity for entrepreneurs to get feedback and for investors to discover the next big thing.',
                'location'      => 'Davao City, Philippines',
                'venue'         => 'Gmall Activity Center',
                'start_date'    => now()->addDays(10),
                'end_date'      => now()->addDays(10),
                'category'      => 'Business',
                'status'        => 'published',
                'max_attendees' => 150,
                'ticket_price'  => 25.00,
                'is_featured'   => false,
            ],
        ];

        foreach ($upcoming as $data) {
            $data['user_id'] = $admin->id;
            $data['slug']    = Str::slug($data['title']) . '-' . Str::random(5);
            Event::create($data);
        }

        // ── PAST events — auto-show in Archive (end_date in the past) ───────
        $past = [
            [
                'title'         => 'Laravel Philippines Meetup 2024',
                'description'   => 'A community gathering for Laravel developers across the Philippines. Featured talks on Laravel 11, Livewire, and best practices for building scalable apps.',
                'location'      => 'Makati City, Philippines',
                'venue'         => 'Ayala Tower One',
                'start_date'    => now()->subDays(30),
                'end_date'      => now()->subDays(29),
                'category'      => 'Technology',
                'status'        => 'published',   // published + past end_date = auto-archived
                'max_attendees' => 200,
                'ticket_price'  => 0,
                'is_featured'   => false,
            ],
            [
                'title'         => 'Digital Marketing Summit 2024',
                'description'   => 'Two days of cutting-edge digital marketing strategies, SEO insights, and social media growth tactics from top industry experts.',
                'location'      => 'Cebu City, Philippines',
                'venue'         => 'Waterfront Hotel',
                'start_date'    => now()->subDays(60),
                'end_date'      => now()->subDays(59),
                'category'      => 'Business',
                'status'        => 'published',
                'max_attendees' => 400,
                'ticket_price'  => 199.00,
                'is_featured'   => false,
            ],
            [
                'title'         => 'Davao Food & Culture Festival 2024',
                'description'   => 'A vibrant celebration of Davao\'s rich culinary heritage and cultural traditions. Featuring local delicacies, live music, and cultural performances.',
                'location'      => 'Davao City, Philippines',
                'venue'         => 'SM Lanang Premier',
                'start_date'    => now()->subDays(45),
                'end_date'      => now()->subDays(43),
                'category'      => 'Entertainment',
                'status'        => 'completed',   // explicitly completed
                'max_attendees' => 3000,
                'ticket_price'  => 50.00,
                'is_featured'   => false,
            ],
            [
                'title'         => 'Web Development Bootcamp 2024',
                'description'   => 'An intensive 3-day bootcamp covering HTML, CSS, JavaScript, and PHP fundamentals. Perfect for beginners starting their web development journey.',
                'location'      => 'Manila, Philippines',
                'venue'         => 'DLSU Manila',
                'start_date'    => now()->subDays(90),
                'end_date'      => now()->subDays(88),
                'category'      => 'Education',
                'status'        => 'completed',
                'max_attendees' => 80,
                'ticket_price'  => 99.00,
                'is_featured'   => false,
            ],
            [
                'title'         => 'Mindanao Sports Festival 2024',
                'description'   => 'The premier multi-sport event in Mindanao showcasing athletic excellence across basketball, volleyball, football, and swimming.',
                'location'      => 'General Santos City, Philippines',
                'venue'         => 'GenSan Sports Complex',
                'start_date'    => now()->subDays(20),
                'end_date'      => now()->subDays(18),
                'category'      => 'Sports',
                'status'        => 'published',
                'max_attendees' => 5000,
                'ticket_price'  => 30.00,
                'is_featured'   => false,
            ],
        ];

        foreach ($past as $data) {
            $data['user_id'] = $admin->id;
            $data['slug']    = Str::slug($data['title']) . '-' . Str::random(5);
            $event = Event::create($data);

            // Give the member a registration on each past event
            EventRegistration::firstOrCreate(
                ['user_id' => $member->id, 'event_id' => $event->id],
                [
                    'status'        => 'confirmed',
                    'ticket_number' => 'TKT-' . strtoupper(Str::random(8)),
                ]
            );
        }

        // ── PENDING APPROVAL demo ───────────────────────────────────────────
        Event::create([
            'user_id'       => $member->id,
            'title'         => 'Community Health Drive 2025',
            'slug'          => 'community-health-drive-2025-' . Str::random(5),
            'description'   => 'A free community health screening event offering blood pressure checks, blood sugar tests, dental consultations, and general health advice for all ages.',
            'location'      => 'Davao City, Philippines',
            'venue'         => 'Barangay Hall, Matina',
            'start_date'    => now()->addDays(25),
            'end_date'      => now()->addDays(25),
            'category'      => 'Health',
            'status'        => 'pending_approval',
            'max_attendees' => 500,
            'ticket_price'  => 0,
            'is_featured'   => false,
        ]);

        $this->command->info('✅ Demo events seeded successfully!');
        $this->command->info('   Admin: admin@events.com / password');
        $this->command->info('   Member: john@example.com / password');
    }
}
