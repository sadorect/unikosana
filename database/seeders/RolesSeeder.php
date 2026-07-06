<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Content Admins may manage these resources; everything else
     * (settings pages, user management) stays with Super Admin.
     */
    protected array $contentResources = [
        'event',
        'album',
        'post',
        'resource',
        'member',
        'leadership',
        'faq',
        'testimonial',
        'contact::message',
    ];

    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $contentAdmin = Role::firstOrCreate(['name' => 'content_admin']);
        // Members log in to the public member portal only — no admin panel access.
        Role::firstOrCreate(['name' => 'member']);

        // Grant the content admin every permission tied to the content resources.
        // Shield-generated permissions look like "view_event", "create_post", etc.
        $permissions = Permission::all();

        $contentPermissions = $permissions->filter(function (Permission $permission) {
            foreach ($this->contentResources as $resource) {
                if (str_contains($permission->name, $resource)) {
                    return true;
                }
            }

            return false;
        });

        $contentAdmin->syncPermissions($contentPermissions);

        // First Super Admin account.
        $admin = User::firstOrCreate(
            ['email' => 'admin@unikosa-na.test'],
            ['name' => 'Site Administrator', 'password' => Hash::make('password')],
        );
        $admin->syncRoles([$superAdmin]);

        // A demo Content Admin account.
        $editor = User::firstOrCreate(
            ['email' => 'editor@unikosa-na.test'],
            ['name' => 'Content Editor', 'password' => Hash::make('password')],
        );
        $editor->syncRoles([$contentAdmin]);
    }
}
