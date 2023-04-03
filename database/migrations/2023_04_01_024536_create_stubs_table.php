<?php

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
        Schema::create('stubs', function (Blueprint $table) {
            $table->id();

            $table->string('prompt')->nullable();
            $table->unsignedInteger('from');
            $table->unsignedInteger('to');

            $table->foreignIdFor(\App\Models\Source::class);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stubs');
    }
};
