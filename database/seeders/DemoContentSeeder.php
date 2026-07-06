<?php

namespace Database\Seeders;

use App\Enums\EventStatus;
use App\Enums\PostType;
use App\Enums\ResourceCategory;
use App\Models\Event;
use App\Models\Leadership;
use App\Models\Member;
use App\Models\Post;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DemoContentSeeder extends Seeder
{
    public function run(): void
    {
        $author = User::where('email', 'admin@unikosa-na.test')->first();

        // Leadership
        $leaders = [
            ['President', 'Adaeze Okafor'],
            ['Vice President', 'Michael Adeyemi'],
            ['Secretary', 'Ngozi Eze'],
            ['Treasurer', 'David Okonkwo'],
            ['Public Relations Officer', 'Fatima Bello'],
        ];
        foreach ($leaders as $i => [$position, $name]) {
            Leadership::firstOrCreate(
                ['name' => $name],
                [
                    'position' => $position,
                    'email' => strtolower(str_replace(' ', '.', $name)) . '@unikosa-na.test',
                    'biography' => "{$name} serves as the {$position} of the North America Unit, bringing dedication and experience to the role.",
                    'sort_order' => $i,
                    'is_active' => true,
                ],
            );
        }

        // Members
        $states = ['California', 'Texas', 'New York', 'Ontario', 'Florida', 'Illinois'];
        $schools = ['University of Lagos', 'University of Ibadan', 'Ahmadu Bello University', 'University of Nigeria'];
        $occupations = ['Software Engineer', 'Physician', 'Accountant', 'Entrepreneur', 'Professor', 'Nurse'];
        for ($i = 1; $i <= 24; $i++) {
            Member::firstOrCreate(
                ['membership_id' => 'NA-' . str_pad((string) $i, 4, '0', STR_PAD_LEFT)],
                [
                    'full_name' => fake()->name(),
                    'state_province' => $states[array_rand($states)],
                    'country' => $i % 5 === 0 ? 'Canada' : 'United States',
                    'occupation' => $occupations[array_rand($occupations)],
                    'school' => $schools[array_rand($schools)],
                    'graduation_year' => rand(1995, 2020),
                    'biography' => fake()->sentence(14),
                    'is_public' => true,
                ],
            );
        }

        // Events
        $events = [
            ['Annual General Meeting 2026', now()->addMonths(2), EventStatus::Upcoming, true],
            ['North America Summer Convention', now()->addMonths(4), EventStatus::Upcoming, true],
            ['Community Health Webinar', now()->addWeeks(3), EventStatus::Upcoming, false],
            ['Leadership Retreat 2025', now()->subMonths(5), EventStatus::Completed, false],
            ['Year-End Gala 2025', now()->subMonths(7), EventStatus::Completed, false],
            ['Youth Mentorship Day 2024', Carbon::parse('2024-09-14'), EventStatus::Completed, false],
        ];
        foreach ($events as [$title, $date, $status, $featured]) {
            Event::firstOrCreate(
                ['title' => $title],
                [
                    'date' => $date,
                    'venue' => fake()->city() . ' Convention Center',
                    'description' => '<p>' . fake()->paragraph(4) . '</p>',
                    'status' => $status,
                    'is_featured' => $featured,
                    'speakers' => [
                        ['name' => fake()->name(), 'title' => 'Keynote Speaker'],
                        ['name' => fake()->name(), 'title' => 'Guest Panelist'],
                    ],
                ],
            );
        }

        // Posts
        $posts = [
            ['Welcome to the New Unikosa North America Website', PostType::Announcement, true],
            ['Highlights from the 2025 Year-End Gala', PostType::News, true],
            ['A Message from the President', PostType::Article, false],
            ['Press Release: New Executive Council Inaugurated', PostType::PressRelease, false],
            ['Scholarship Program Now Accepting Applications', PostType::Announcement, false],
        ];
        foreach ($posts as $i => [$title, $type, $featured]) {
            Post::firstOrCreate(
                ['title' => $title],
                [
                    'type' => $type,
                    'author_id' => $author?->id,
                    'excerpt' => fake()->sentence(18),
                    'body' => '<p>' . implode('</p><p>', fake()->paragraphs(4)) . '</p>',
                    'category' => 'General',
                    'is_featured' => $featured,
                    'published_at' => now()->subDays($i * 5),
                ],
            )->syncTags(['community', 'north-america']);
        }

        // Resources
        $resources = [
            ['Unikosa North America Constitution', ResourceCategory::Constitution],
            ['2025 Annual Report', ResourceCategory::Report],
            ['AGM Meeting Minutes — March 2025', ResourceCategory::Minutes],
            ['Membership Registration Form', ResourceCategory::Form],
            ['Q2 2025 Newsletter', ResourceCategory::Newsletter],
        ];
        foreach ($resources as $i => [$title, $category]) {
            Resource::firstOrCreate(
                ['title' => $title],
                [
                    'category' => $category,
                    'description' => fake()->sentence(10),
                    'is_published' => true,
                    'sort_order' => $i,
                ],
            );
        }
    }
}
