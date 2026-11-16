<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->string('clean_phone', 40)->nullable()->after('phone');
            $table->index(['stand_id', 'clean_phone'], 'leads_stand_clean_phone_idx');
        });

        // Población inicial de clean_phone para registros existentes en PostgreSQL
        DB::statement("UPDATE leads SET clean_phone = REGEXP_REPLACE(phone, '[^0-9]', '', 'g') WHERE clean_phone IS NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropIndex('leads_stand_clean_phone_idx');
            $table->dropColumn('clean_phone');
        });
    }
};
