<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Use the already-migrated & seeded dev database (read-only GET checks).
        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite.database', database_path('database.sqlite'));
        DB::purge('sqlite');
    }

    public function test_super_admin_reaches_dashboard_and_settings(): void
    {
        $admin = User::where('email', 'admin@unikosa-na.test')->firstOrFail();

        $this->actingAs($admin)->get('/admin')->assertOk();
        $this->actingAs($admin)->get('/admin/manage-theme')->assertOk();
        $this->actingAs($admin)->get('/admin/events')->assertOk();
    }

    public function test_content_admin_manages_content_but_not_theme(): void
    {
        $editor = User::where('email', 'editor@unikosa-na.test')->firstOrFail();

        $this->actingAs($editor)->get('/admin/events')->assertOk();
        $this->actingAs($editor)->get('/admin/members')->assertOk();
        $this->actingAs($editor)->get('/admin/manage-theme')->assertForbidden();
    }

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
    }
}
