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
        Schema::create('speletajs', function (Blueprint $table) {
            $table->id();
            $table->string('vards');
            $table->string('uzvards');
            $table->foreignId('komanda_id')->constrained('komandas')->cascadeOnDelete();
            $table->smallInteger('nepamekletieTrenini');
            $table->smallInteger('speles');
            $table->smallInteger('varti');
            $table->smallInteger('piespeles');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('speletajs');
    }
};
