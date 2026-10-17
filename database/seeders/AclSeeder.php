<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class AclSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Keep primary keys and user assignments intact when reseeding.
        $admin = Role::updateOrCreate(['name' => 'admin'], [
            'label' => 'Administrator',
        ]);

        $mod = Role::updateOrCreate(['name' => 'mod'], [
            'label' => 'Moderator',
        ]);

        Role::updateOrCreate(['name' => 'wiki_admin'], ['label' => 'Wiki Administrator']);
        Role::updateOrCreate(['name' => 'wiki_moderator'], ['label' => 'Wiki Moderator']);

        // Permissions
        // Content Management
        $editEvent = Permission::updateOrCreate(['name' => 'edit_event'], [
            'label' => 'Edit and Create Events',
        ]);

        $editReport = Permission::updateOrCreate(['name' => 'edit_report'], [
            'label' => 'Edit Reports',
        ]);

        $reviewReport = Permission::updateOrCreate(['name' => 'review_report'], [
            'label' => 'Review Reports',
        ]);

        $editTag = Permission::updateOrCreate(['name' => 'edit_tag'], [
            'label' => 'Edit Tags',
        ]);

        // Administration
        $viewAdministration = Permission::updateOrCreate(['name' => 'admin_view'], [
            'label' => 'View Administration Pages',
        ]);
        $manageUser = Permission::updateOrCreate(['name' => 'admin_manage_user'], [
            'label' => 'View User Management Pages',
        ]);
        $managePermissions = Permission::updateOrCreate(['name' => 'admin_manage_permissions'], [
            'label' => 'Manage Permissions',
        ]);
        $banPermissions = Permission::updateOrCreate(['name' => 'admin_ban'], [
            'label' => 'Create Bans',
        ]);
        $assumeUser = Permission::updateOrCreate(['name' => 'admin_assume_user'], [
            'label' => 'Assume the Identify of a User',
        ]);
        $resetPassword = Permission::updateOrCreate(['name' => 'admin_reset_password'], [
            'label' => 'Initiate a User password reset',
        ]);
        $deleteUser = Permission::updateOrCreate(['name' => 'admin_delete_user'], [
            'label' => 'Deletes a Users account',
        ]);

        // Role Permissions
        $mod->permissions()->syncWithoutDetaching([
            $viewAdministration->id,
            $manageUser->id,
            $editEvent->id,
            $editReport->id,
            $reviewReport->id,
            $editTag->id,
            $resetPassword->id,
        ]);
        $admin->permissions()->syncWithoutDetaching(Permission::all()->modelKeys());

        // Invalidate the cached permission-to-role map.
        cache()->forget('permissions');
        cache()->forget('hasPermissions');
    }
}
