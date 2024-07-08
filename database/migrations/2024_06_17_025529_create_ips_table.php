<?php

use App\Models\Ip;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ips', function (Blueprint $table) {
            $table->id();
            $table->string('ip', 45);
            $table->timestamps();
        });

        Schema::create('ip_user', function (Blueprint $table) {
            $table->foreignIdFor(Ip::class);
            $table->foreignIdFor(User::class);
            $table->timestamps();

            $table->primary(['ip_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ip_user');
        Schema::dropIfExists('ips');
    }
};
