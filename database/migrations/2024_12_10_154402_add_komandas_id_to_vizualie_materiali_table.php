<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('vizualie_materiali', function (Blueprint $table) {
            $table->foreignId('komandas_id')->constrained('komandas')->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::table('vizualie_materiali', function (Blueprint $table) {
            $table->dropForeign(['komandas_id']); // Drop foreign key
            $table->dropColumn('komandas_id');    // Drop the column
        });
    }
};