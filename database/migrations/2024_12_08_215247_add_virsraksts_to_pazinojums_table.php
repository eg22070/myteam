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
        Schema::table('pazinojums', function (Blueprint $table) {
            $table->string('virsraksts')->after('id'); // Adds title column after id
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pazinojums', function (Blueprint $table) {
            //
        });
    }
};
