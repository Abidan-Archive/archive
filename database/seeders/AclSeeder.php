<?php

namespace Database\Seeders;

use DB;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            'label' => 'Administrator'
        ]);

        $mod = Role::create([
            'name' => 'mod',
            'label' => 'Moderator'
        ]);

        // Permissions
        // Content Management
        $editEvent = Permission::create([
            'name' => 'edit_event',
            'label' => 'Edit and Create Events'
        ]);

        $editReport = Permission::create([
            'name' => 'edit_report',
            'label' => 'Edit Reports'
        ]);

        $reviewReport = Permission::create([
            'name' => 'review_report',
            'label' => 'Review Reports'
        ]);

        $editTag = Permission::create([
            'name' => 'edit_tag',
            'label' => 'Edit Tags'
        ]);

        // Administration
        $viewAdministration = Permission::create([
            'name' => 'view_admin',
            'label' => 'View Administration Pages'
        ]);
        $managePermissions = Permission::create([
            'name' => 'manage_permissions',
            'label' => 'Manage Permissions'
        ]);
        $assumeUser = Permission::create([
            'name' => 'assume_user',
            'label' => 'Assume the Identify of a User',
        ]);


        // Role Permissions
        $mod->permissions()->saveMany([
            $viewAdministration,
            $editEvent,
            $editReport,
            $reviewReport,
            $editTag,
        ]);
        $admin->permissions()->saveMany(Permission::all());
    }
}
