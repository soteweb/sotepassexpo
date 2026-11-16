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
        // 1. Tabla de Planes de Suscripción Expo B2B
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->string('slug', 60)->unique();
            $table->text('description')->nullable();
            $table->bigInteger('price')->default(0); // Guaraníes (PYG)
            $table->string('badge_text', 50)->nullable();
            $table->jsonb('features')->default('[]');
            $table->boolean('has_ruleta')->default(true);
            $table->boolean('has_tv_draw')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // 2. Modificar tabla de usuarios para roles y datos de empresa
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 30)->default('expositor')->after('password');
            $table->string('phone', 50)->nullable()->after('role');
            $table->string('company_name', 150)->nullable()->after('phone');
        });

        // 3. Modificar stands para vincular con usuario dueño y plan contratado
        Schema::table('stands', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
            $table->foreignId('plan_id')->nullable()->after('user_id')->constrained('plans')->nullOnDelete();
            $table->string('status', 30)->default('active')->after('spin_policy'); // active, pending, suspended
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stands', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['plan_id']);
            $table->dropColumn(['user_id', 'plan_id', 'status']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'phone', 'company_name']);
        });

        Schema::dropIfExists('plans');
    }
};
