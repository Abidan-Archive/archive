<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use DB;
use Illuminate\Database\Seeder;

class AclSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('permissions')->delete();
        DB::table('roles')->delete();

        // Roles
        $admin = Role::create([
            'name' => 'admin',
            'label' => 'Administrator',
        ]);

        $mod = Role::create([
            'name' => 'mod',
            'label' => 'Moderator',
        ]);

        // Permissions
        // Content Management
        $editEvent = Permission::create([
            'name' => 'edit_event',
            'label' => 'Edit and Create Events',
        ]);

        $editReport = Permission::create([
            'name' => 'edit_report',
            'label' => 'Edit Reports',
        ]);

        $reviewReport = Permission::create([
            'name' => 'review_report',
            'label' => 'Review Reports',
        ]);

        $editTag = Permission::create([
            'name' => 'edit_tag',
            'label' => 'Edit Tags',
        ]);

        // Administration
        $viewAdministration = Permission::create([
            'name' => 'admin_view',
            'label' => 'View Administration Pages',
        ]);
        $managePermissions = Permission::create([
            'name' => 'admin_manage_permissions',
            'label' => 'Manage Permissions',
        ]);
        $banPermissions = Permission::create([
            'name' => 'admin_ban',
            'label' => 'Create Bans'
        ]);
        $assumeUser = Permission::create([
            'name' => 'admin_assume_user',
            'label' => 'Assume the Identify of a User',
        ]);
        $resetPassword = Permission::create([
            'name' => 'admin_reset_password',
            'label' => 'Sets a Users password to a random string and asks them to reset via email',
        ]);

        // Role Permissions
        $mod->permissions()->saveMany([
            $viewAdministration,
            $editEvent,
            $editReport,
            $reviewReport,
            $editTag,
            $resetPassword,
        ]);
        $admin->permissions()->saveMany(Permission::all());
    }
}
