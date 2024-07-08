<?php

use App\Models\Report;
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
        Schema::create('dialogues', function (Blueprint $table) {
            $table->id();
            $table->string('speaker');
            $table->longText('line');
            $table->integer('order')->unsigned();

            $table->foreignIdFor(Report::class);
            $table->fullText('line')->language('english');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dialogues');
    }
};
