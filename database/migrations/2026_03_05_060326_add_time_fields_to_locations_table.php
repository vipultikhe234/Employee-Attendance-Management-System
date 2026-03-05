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
        Schema::table('locations', function (Blueprint $table) {
            $table->time('in_time')->default('09:00:00');
            $table->time('out_time')->default('17:00:00');
            $table->integer('early_buffer')->default(30);
            $table->integer('late_buffer')->default(20);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->dropColumn(['in_time', 'out_time', 'early_buffer', 'late_buffer']);
        });
    }
};
