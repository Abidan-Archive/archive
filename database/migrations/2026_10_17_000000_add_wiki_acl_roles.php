<?php

use App\Models\Role;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Role::firstOrCreate(['name' => 'wiki_admin'], ['label' => 'Wiki Administrator']);
        Role::firstOrCreate(['name' => 'wiki_moderator'], ['label' => 'Wiki Moderator']);
    }

    public function down(): void
    {
        Role::whereIn('name', ['wiki_admin', 'wiki_moderator'])->delete();
    }
};
