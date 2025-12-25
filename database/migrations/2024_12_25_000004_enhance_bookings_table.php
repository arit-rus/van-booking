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
        Schema::table('bookings', function (Blueprint $table) {
            // Rename origin to pickup_location
            $table->renameColumn('origin', 'pickup_location');
            
            // Add requested department
            $table->enum('requested_department', ['gad', 'subnon', 'subwa', 'subsu'])
                ->nullable()
                ->after('purpose');
            
            // Add attachment path
            $table->string('attachment_path')->nullable()->after('requested_department');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->renameColumn('pickup_location', 'origin');
            $table->dropColumn(['requested_department', 'attachment_path']);
        });
    }
};
