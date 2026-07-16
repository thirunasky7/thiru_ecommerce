<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('vendors')) {
            return;
        }

        // Expand vendor status for partner registration / approval.
        DB::statement("ALTER TABLE vendors MODIFY status ENUM('pending','active','inactive','banned','rejected') NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        if (!Schema::hasTable('vendors')) {
            return;
        }

        DB::table('vendors')->whereIn('status', ['pending', 'rejected'])->update(['status' => 'inactive']);
        DB::statement("ALTER TABLE vendors MODIFY status ENUM('active','inactive','banned') NOT NULL DEFAULT 'active'");
    }
};
