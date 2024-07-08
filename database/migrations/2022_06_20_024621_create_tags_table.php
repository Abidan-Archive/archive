<?php

use App\Models\Report;
use App\Models\Tag;
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
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name', 64)->unique();
            $table->string('color', 6);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('report_tag', function (Blueprint $table) {
            $table->foreignIdFor(Report::class);
            $table->foreignIdFor(Tag::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_tag');
        Schema::dropIfExists('tags');
    }
};
