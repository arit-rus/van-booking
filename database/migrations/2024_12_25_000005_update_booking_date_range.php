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
        // First: rename columns
        Schema::table('bookings', function (Blueprint $table) {
            $table->renameColumn('travel_date', 'start_date');
            $table->renameColumn('departure_time', 'start_time');
            $table->renameColumn('return_time', 'end_time');
        });
        
        // Second: add end_date column (after renames completed)
        Schema::table('bookings', function (Blueprint $table) {
            $table->date('end_date')->nullable()->after('start_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('end_date');
        });
        
        Schema::table('bookings', function (Blueprint $table) {
            $table->renameColumn('start_date', 'travel_date');
            $table->renameColumn('start_time', 'departure_time');
            $table->renameColumn('end_time', 'return_time');
        });
    }
};
