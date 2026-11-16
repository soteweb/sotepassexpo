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
        Schema::table('stands', function (Blueprint $table) {
            $table->string('event_name', 120)->nullable()->after('sector');
            $table->date('event_start_date')->nullable()->after('event_name');
            $table->date('event_end_date')->nullable()->after('event_start_date');
            $table->string('payment_status', 30)->default('pending')->after('status'); // pending, submitted, paid, exempt
            $table->string('payment_proof_path', 255)->nullable()->after('payment_status');
            $table->string('payment_reference', 100)->nullable()->after('payment_proof_path');
            $table->boolean('is_trial')->default(true)->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stands', function (Blueprint $table) {
            $table->dropColumn([
                'event_name',
                'event_start_date',
                'event_end_date',
                'payment_status',
                'payment_proof_path',
                'payment_reference',
                'is_trial',
            ]);
        });
    }
};
