<?php

use App\Models\Source;
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
            $table->unsignedBigInteger('id');
            $table->foreignIdFor(Source::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->unsignedInteger('from');
            $table->unsignedInteger('to');
            $table->string('prompt')->nullable();


            $table->timestamps();

            $table->primary(['id', 'source_id']);
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
