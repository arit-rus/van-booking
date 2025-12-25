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
        Schema::table('vans', function (Blueprint $table) {
            $table->enum('owner_department', ['gad', 'subnon', 'subwa', 'subsu'])
                ->default('gad')
                ->after('campus');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vans', function (Blueprint $table) {
            $table->dropColumn('owner_department');
        });
    }
};
