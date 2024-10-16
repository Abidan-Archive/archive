<?php

use App\Models\Event;
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
        Schema::create('sources', function (Blueprint $table) {
            $table->unsignedBigInteger('id');
            $table->foreignIdFor(Event::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->string('name');
            $table->string('filename')->nullable(); // Only null on initial upload

            $table->timestamps();
            $table->primary(['id', 'event_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sources');
    }
};
