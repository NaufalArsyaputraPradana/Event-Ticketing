<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Added this import for DB facade

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, we need to drop the existing enum constraint
        DB::statement("ALTER TABLE tickets MODIFY COLUMN status VARCHAR(20)");
        
        // Then recreate the enum with the new values
        DB::statement("ALTER TABLE tickets MODIFY COLUMN status ENUM('pending', 'active', 'used', 'cancelled') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum values
        DB::statement("ALTER TABLE tickets MODIFY COLUMN status VARCHAR(20)");
        DB::statement("ALTER TABLE tickets MODIFY COLUMN status ENUM('active', 'used', 'cancelled') DEFAULT 'active'");
    }
};
