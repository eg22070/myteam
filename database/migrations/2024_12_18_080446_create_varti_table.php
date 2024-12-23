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
        Schema::create('varti', function (Blueprint $table) {
            $table->id();
            $table->foreignId('speles_id')->constrained('speles')->onDelete('cascade'); // Reference to the game
            $table->foreignId('player_id')->constrained('speletajs')->onDelete('cascade'); // Goal scorer
            $table->foreignId('assist_id')->nullable()->constrained('speletajs')->onDelete('cascade'); // Assister (nullable)
            $table->integer('minute'); // Minute of the goal
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('varti');
    }
};
