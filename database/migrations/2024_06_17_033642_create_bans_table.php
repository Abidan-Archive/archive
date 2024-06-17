<?php

use App\Models\Ban;
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
        Schema::create('bans', function (Blueprint $table) {
            $table->id();
            $table->timestamp('expires')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('bannables', function (Blueprint $table) {
            $table->foreignIdFor(Ban::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->morphs('bannable');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bannables');
        Schema::dropIfExists('bans');
    }
};
