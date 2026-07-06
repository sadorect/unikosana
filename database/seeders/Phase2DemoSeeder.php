<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class Phase2DemoSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            ['How do I become a member of Unikosa North America?', 'You can register through our online membership form on the Join page, or contact any executive. New members are reviewed and approved by the administration.', 'Membership'],
            ['Is there a membership fee?', 'Membership dues are set annually by the executive council. Details are shared during the registration process.', 'Membership'],
            ['How can I attend an event?', 'Browse the Events page for upcoming gatherings. Where registration is required, an RSVP button will appear on the event page.', 'Events'],
            ['How do I update my directory profile?', 'Registered members can log in to the member portal and edit their own profile at any time.', 'Membership'],
            ['Can I make a donation to support the unit?', 'Yes. Visit the Donate page to see the ways you can support our programs and initiatives.', 'Giving'],
        ];
        foreach ($faqs as $i => [$q, $a, $cat]) {
            Faq::firstOrCreate(['question' => $q], ['answer' => $a, 'category' => $cat, 'sort_order' => $i]);
        }

        $testimonials = [
            ['Adaeze Okafor', 'Member since 2018', 'Being part of Unikosa North America has connected me with an incredible network of professionals who share my values.'],
            ['Michael Adeyemi', 'Toronto, Canada', 'The events and mentorship opportunities have been invaluable to my career and my family.'],
            ['Ngozi Eze', 'Houston, TX', 'This community feels like home away from home. I am proud to be a member.'],
        ];
        foreach ($testimonials as $i => [$name, $title, $quote]) {
            Testimonial::firstOrCreate(
                ['name' => $name],
                ['title' => $title, 'quote' => $quote, 'sort_order' => $i, 'is_featured' => true, 'is_published' => true],
            );
        }
    }
}
