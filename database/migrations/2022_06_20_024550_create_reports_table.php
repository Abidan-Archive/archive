<?php

use App\Models\Event;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('footnote')->nullable();
            $table->date('date');
            $table->string('source_label')->nullable();
            $table->string('source_href')->nullable();

            $table->string('legacy_permalink')->nullable();

            $table->foreignIdFor(Event::class);

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('report_likes', function(Blueprint $table) {
            $table->foreignIdFor(Report::class);
            $table->foreignIdFor(User::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reports');
        Schema::dropIfExists('report_likes');
    }
};
