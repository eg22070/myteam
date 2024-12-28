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
        Schema::create('zinas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sutitaja_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('sanemeja_id')->constrained('users')->onDelete('cascade');
            $table->text('zinas_saturs');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zinas');
    }
};