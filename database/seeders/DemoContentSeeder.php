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

        // ---------------------------------------------------------------
        // Leadership — Executive Council of the North America Unit
        // ---------------------------------------------------------------
        $leaders = [
            ['President', 'Adaeze Okafor', 'Adaeze Okafor is a healthcare administrator in Sacramento and has served the North America Unit for over a decade. As President she has championed the unit\'s scholarship fund and expanded its chapter network across the United States and Canada.'],
            ['Vice President', 'Michael Adeyemi', 'Michael Adeyemi is a project management consultant based in Toronto. He coordinates the unit\'s regional chapters and leads the planning committee for the annual convention.'],
            ['Secretary', 'Ngozi Eze', 'Ngozi Eze is an attorney in Houston who oversees the unit\'s records, communications, and governance. She authored the current membership handbook and led the latest constitution review.'],
            ['Treasurer', 'David Okonkwo', 'David Okonkwo is a certified public accountant in Atlanta. He manages the unit\'s finances, publishes the annual report, and administers the members\' welfare and benevolence fund.'],
            ['Public Relations Officer', 'Fatima Bello', 'Fatima Bello is a communications professional in New York. She leads the unit\'s media, social outreach, and community partnership programs.'],
        ];
        foreach ($leaders as $i => [$position, $name, $bio]) {
            Leadership::firstOrCreate(
                ['name' => $name],
                [
                    'position' => $position,
                    'email' => strtolower(str_replace(' ', '.', $name)) . '@unikosa-na.test',
                    'biography' => $bio,
                    'sort_order' => $i,
                    'is_active' => true,
                ],
            );
        }

        // ---------------------------------------------------------------
        // Members — curated directory of alumni professionals
        // ---------------------------------------------------------------
        $members = [
            ['Chinedu Nwosu', 'California', 'United States', 'Software Engineer', 'University of Nigeria, Nsukka', 2012],
            ['Amara Obi', 'Texas', 'United States', 'Physician', 'University of Ibadan', 2009],
            ['Tunde Balogun', 'New York', 'United States', 'Financial Analyst', 'University of Lagos', 2014],
            ['Zainab Yusuf', 'Ontario', 'Canada', 'Pharmacist', 'Ahmadu Bello University', 2011],
            ['Emeka Obiora', 'Illinois', 'United States', 'Civil Engineer', 'University of Nigeria, Nsukka', 2008],
            ['Halima Sani', 'Florida', 'United States', 'Registered Nurse', 'Ahmadu Bello University', 2016],
            ['Obinna Eze', 'California', 'United States', 'Data Scientist', 'University of Ibadan', 2017],
            ['Funmilayo Adebayo', 'New York', 'United States', 'University Professor', 'University of Lagos', 2003],
            ['Ibrahim Musa', 'Ontario', 'Canada', 'Mechanical Engineer', 'Ahmadu Bello University', 2013],
            ['Chioma Okeke', 'Texas', 'United States', 'Entrepreneur', 'University of Nigeria, Nsukka', 2010],
            ['Segun Afolabi', 'Illinois', 'United States', 'Architect', 'University of Lagos', 2007],
            ['Blessing Adewale', 'Florida', 'United States', 'Accountant', 'University of Ibadan', 2015],
            ['Kunle Ogunleye', 'California', 'United States', 'Physician', 'University of Ibadan', 2006],
            ['Ada Nnamdi', 'New York', 'United States', 'Attorney', 'University of Nigeria, Nsukka', 2012],
            ['Musa Danladi', 'Ontario', 'Canada', 'IT Consultant', 'Ahmadu Bello University', 2014],
            ['Ifeoma Umeh', 'Texas', 'United States', 'Public Health Specialist', 'University of Ibadan', 2013],
            ['Bode Salako', 'Illinois', 'United States', 'Entrepreneur', 'University of Lagos', 2005],
            ['Grace Effiong', 'Florida', 'United States', 'Registered Nurse', 'University of Nigeria, Nsukka', 2018],
            ['Yakubu Bello', 'Ontario', 'Canada', 'University Professor', 'Ahmadu Bello University', 2001],
            ['Ngozi Okoro', 'California', 'United States', 'Software Engineer', 'University of Lagos', 2019],
            ['Daniel Ojo', 'New York', 'United States', 'Financial Analyst', 'University of Ibadan', 2011],
            ['Aisha Garba', 'Texas', 'United States', 'Pharmacist', 'Ahmadu Bello University', 2016],
            ['Chukwuma Anyanwu', 'Illinois', 'United States', 'Civil Engineer', 'University of Nigeria, Nsukka', 2009],
            ['Rukayat Lawal', 'Florida', 'United States', 'Physician', 'University of Lagos', 2010],
        ];
        foreach ($members as $i => [$name, $state, $country, $occupation, $school, $gradYear]) {
            $city = $this->cityFor($state);
            Member::firstOrCreate(
                ['membership_id' => 'NA-' . str_pad((string) ($i + 1), 4, '0', STR_PAD_LEFT)],
                [
                    'full_name' => $name,
                    'state_province' => $state,
                    'country' => $country,
                    'occupation' => $occupation,
                    'school' => $school,
                    'graduation_year' => $gradYear,
                    'biography' => "{$name} is a {$occupation} based in {$city}, {$state}, and a graduate of {$school} ({$gradYear}). An active member of the North America Unit, {$name} supports the community through mentorship and volunteering.",
                    'is_public' => true,
                ],
            );
        }

        // ---------------------------------------------------------------
        // Events
        // ---------------------------------------------------------------
        $events = [
            [
                'Annual General Meeting 2026', now()->addMonths(2), 'Chicago, Illinois', EventStatus::Upcoming, true,
                'The Annual General Meeting brings members together to review the past year, approve the budget, and elect the incoming Executive Council. All financial members are encouraged to attend and vote. A hybrid option will be available for members unable to travel.',
                [['name' => 'Adaeze Okafor', 'title' => 'President\'s Address'], ['name' => 'David Okonkwo', 'title' => 'Treasurer\'s Financial Report']],
            ],
            [
                'North America Summer Convention', now()->addMonths(4), 'Houston, Texas', EventStatus::Upcoming, true,
                'Our flagship three-day convention features professional development workshops, a cultural gala, a youth program, and networking sessions connecting members across every chapter. This year\'s theme is "Building Bridges, Sustaining Legacy."',
                [['name' => 'Dr. Amara Obi', 'title' => 'Keynote: Health in the Diaspora'], ['name' => 'Segun Afolabi', 'title' => 'Panel: Entrepreneurship Abroad']],
            ],
            [
                'Community Health Webinar', now()->addWeeks(3), 'Online (Zoom)', EventStatus::Upcoming, false,
                'A free public webinar with physicians and nurses from our membership covering preventive care, mental health, and navigating the healthcare system in North America. A live Q&A follows the presentations.',
                [['name' => 'Dr. Kunle Ogunleye', 'title' => 'Preventive Care'], ['name' => 'Grace Effiong, RN', 'title' => 'Navigating Health Coverage']],
            ],
            [
                'Leadership Retreat 2025', now()->subMonths(5), 'Niagara Falls, Ontario', EventStatus::Completed, false,
                'Chapter leaders gathered for a weekend of strategic planning, governance training, and team building. The retreat produced the unit\'s three-year growth roadmap and a renewed chapter support framework.',
                [['name' => 'Michael Adeyemi', 'title' => 'Facilitator']],
            ],
            [
                'Year-End Gala 2025', now()->subMonths(7), 'Atlanta, Georgia', EventStatus::Completed, false,
                'An elegant evening celebrating the achievements of the year, honoring outstanding members, and raising funds for the scholarship program. The gala welcomed over 300 guests and raised a record amount for student support.',
                [['name' => 'Fatima Bello', 'title' => 'Host']],
            ],
            [
                'Youth Mentorship Day 2024', Carbon::parse('2024-09-14'), 'Newark, New Jersey', EventStatus::Completed, false,
                'A day dedicated to our next generation, pairing high-school and college students with professional mentors for guidance on careers, college applications, and cultural identity in the diaspora.',
                [['name' => 'Prof. Funmilayo Adebayo', 'title' => 'Mentor Lead']],
            ],
        ];
        foreach ($events as [$title, $date, $venue, $status, $featured, $description, $speakers]) {
            Event::firstOrCreate(
                ['title' => $title],
                [
                    'date' => $date,
                    'venue' => $venue,
                    'description' => '<p>' . $description . '</p>',
                    'status' => $status,
                    'is_featured' => $featured,
                    'speakers' => $speakers,
                ],
            );
        }

        // ---------------------------------------------------------------
        // Posts
        // ---------------------------------------------------------------
        $posts = [
            [
                'Welcome to the New Unikosa North America Website', PostType::Announcement, true,
                'We are proud to unveil a brand-new home for our community online.',
                [
                    'After months of planning, we are delighted to launch the new Unikosa North America website. This platform was built to bring our members closer together, wherever they are across the United States and Canada.',
                    'You will find an up-to-date member directory, an events calendar with online registration, a resource library with official documents, and a news section to keep you informed of everything happening across our chapters.',
                    'Members with a national Unikosa account can sign in directly to manage their profile. If you have any feedback, we would love to hear from you through the contact page. Thank you for being part of this community.',
                ],
            ],
            [
                'Highlights from the 2025 Year-End Gala', PostType::News, true,
                'More than 300 members and guests gathered in Atlanta to close out a remarkable year.',
                [
                    'Our 2025 Year-End Gala was an unforgettable celebration of community, culture, and achievement. Guests were treated to an evening of music, cuisine, and heartfelt recognition of the members who gave so much throughout the year.',
                    'The highlight of the night was the scholarship fundraiser, which raised a record amount to support students pursuing higher education. Thanks to the generosity of our members and partners, more young people will receive assistance in the coming academic year.',
                    'We extend our deepest gratitude to the planning committee, our sponsors, and everyone who attended. We look forward to seeing you again at the Summer Convention.',
                ],
            ],
            [
                'A Message from the President', PostType::Article, false,
                'Reflecting on our progress and the road ahead for the North America Unit.',
                [
                    'Dear members, it is my privilege to serve as your President. As I reflect on the past year, I am filled with pride at how much we have accomplished together — from expanding our chapters to strengthening our scholarship and welfare programs.',
                    'Our strength has always been our unity. In a season where many of us are far from home, this community remains a source of belonging, support, and opportunity. That is a legacy worth protecting and growing.',
                    'In the year ahead, my focus is on three priorities: deepening member engagement, investing in our youth, and ensuring every chapter has the support it needs to thrive. I invite each of you to get involved. Together, we build something lasting.',
                ],
            ],
            [
                'Press Release: New Executive Council Inaugurated', PostType::PressRelease, false,
                'The North America Unit swears in its 2025–2027 Executive Council.',
                [
                    'FOR IMMEDIATE RELEASE — Unikosa North America is pleased to announce the inauguration of its new Executive Council for the 2025–2027 term, following elections held at the Annual General Meeting.',
                    'The incoming council is led by President Adaeze Okafor, alongside Vice President Michael Adeyemi, Secretary Ngozi Eze, Treasurer David Okonkwo, and Public Relations Officer Fatima Bello. The team brings decades of combined leadership experience across healthcare, law, finance, and technology.',
                    'The council has pledged to expand the unit\'s scholarship program, grow its chapter network, and enhance member services. For media inquiries, please contact the Public Relations Office through the unit website.',
                ],
            ],
            [
                'Scholarship Program Now Accepting Applications', PostType::Announcement, false,
                'Applications are open for the 2026 academic year student scholarship.',
                [
                    'The Unikosa North America Scholarship Program is now accepting applications for the 2026 academic year. The program supports members and their children pursuing undergraduate and graduate studies.',
                    'Eligible applicants must be in good standing, demonstrate academic merit and financial need, and submit a short essay along with proof of enrollment. Awards are disbursed directly toward tuition.',
                    'The application deadline is the end of the second quarter. Full eligibility criteria and the application form are available in the Resources section of this website. We encourage all qualifying members to apply.',
                ],
            ],
        ];
        foreach ($posts as $i => [$title, $type, $featured, $excerpt, $paragraphs]) {
            Post::firstOrCreate(
                ['title' => $title],
                [
                    'type' => $type,
                    'author_id' => $author?->id,
                    'excerpt' => $excerpt,
                    'body' => '<p>' . implode('</p><p>', $paragraphs) . '</p>',
                    'category' => 'General',
                    'is_featured' => $featured,
                    'published_at' => now()->subDays($i * 5),
                ],
            )->syncTags(['community', 'north-america']);
        }

        // ---------------------------------------------------------------
        // Resources
        // ---------------------------------------------------------------
        $resources = [
            ['Unikosa North America Constitution', ResourceCategory::Constitution, 'The governing constitution of the North America Unit, outlining our mission, membership rules, governance structure, and code of conduct.'],
            ['2025 Annual Report', ResourceCategory::Report, 'A comprehensive review of the unit\'s activities, programs, and audited finances for the 2025 fiscal year.'],
            ['AGM Meeting Minutes — March 2025', ResourceCategory::Minutes, 'Official minutes of the Annual General Meeting, including resolutions passed and the outcome of the executive elections.'],
            ['Membership Registration Form', ResourceCategory::Form, 'The standard form for prospective members to apply to join the North America Unit and its local chapters.'],
            ['Q2 2025 Newsletter', ResourceCategory::Newsletter, 'Our quarterly newsletter featuring chapter updates, member spotlights, upcoming events, and community stories.'],
        ];
        foreach ($resources as $i => [$title, $category, $description]) {
            Resource::firstOrCreate(
                ['title' => $title],
                [
                    'category' => $category,
                    'description' => $description,
                    'is_published' => true,
                    'sort_order' => $i,
                ],
            );
        }
    }

    /**
     * A representative city for a state/province, used to make member
     * profiles read naturally without external data.
     */
    protected function cityFor(string $state): string
    {
        return [
            'California' => 'Sacramento',
            'Texas' => 'Houston',
            'New York' => 'New York City',
            'Ontario' => 'Toronto',
            'Florida' => 'Orlando',
            'Illinois' => 'Chicago',
        ][$state] ?? $state;
    }
}
