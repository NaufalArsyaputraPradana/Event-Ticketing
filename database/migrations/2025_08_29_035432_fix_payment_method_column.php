<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, we need to drop the existing enum constraint
        DB::statement("ALTER TABLE payments MODIFY COLUMN payment_method VARCHAR(50)");
        
        // Then recreate the enum with the new values
        DB::statement("ALTER TABLE payments MODIFY COLUMN payment_method ENUM('bank_transfer', 'ewallet') DEFAULT 'bank_transfer'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum values
        DB::statement("ALTER TABLE payments MODIFY COLUMN payment_method VARCHAR(50)");
        DB::statement("ALTER TABLE payments MODIFY COLUMN payment_method ENUM('pending', 'success', 'failed') DEFAULT 'pending'");
    }
};
