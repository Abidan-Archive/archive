<?php

use App\Models\Dialogue;
use App\Models\Proffer;
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
        Schema::create('proffers', function (Blueprint $table) {
            $table->id();
            $table->string('reason');
            $table->foreignIdFor(Report::class);
            $table->timestamps();
        });

        Schema::create('dialogue_proffer', function (Blueprint $table) {
            $table->foreignIdFor(Proffer::class);
            $table->foreignIdFor(Dialogue::class);
            $table->timestamps();
        });

        // Allow dialgoues to have no associated report
        Schema::table('dialogues', function (Blueprint $table) {
            $table->foreignIdFor(Report::class)->nullable(true)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dialogues', function (Blueprint $table) {
            $table->foreignIdFor(Report::class)->nullable(false)->change();
        });
        Schema::dropIfExists('proffers');
        Schema::dropIfExists('dialogue_proffer');
    }
};
