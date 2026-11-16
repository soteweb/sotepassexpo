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
        // 1. Stands (Empresas expositoras)
        Schema::create('stands', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 60)->unique();
            $table->string('name', 120);
            $table->string('tagline')->nullable();
            $table->string('stand_number', 50);
            $table->string('sector', 100);
            $table->string('primary_color', 20)->default('#16a34a');
            $table->string('secondary_color', 20)->default('#0f172a');
            $table->string('logo_url')->nullable();
            $table->string('access_pin', 20)->default('1234');
            $table->boolean('allow_spins')->default(true);
            $table->integer('max_spins_per_lead')->default(1);
            $table->jsonb('custom_questions')->nullable(); // Potencia de PostgreSQL: preguntas custom
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Prizes (Premios de la Ruleta)
        Schema::create('prizes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stand_id')->constrained('stands')->onDelete('cascade');
            $table->string('name', 100);
            $table->string('color', 30)->default('#22c55e');
            $table->string('text_color', 30)->default('#ffffff');
            $table->integer('total_stock')->default(50);
            $table->integer('remaining_stock')->default(50);
            $table->integer('probability_weight')->default(10);
            $table->boolean('is_real_prize')->default(true);
            $table->string('icon_badge', 50)->default('gift');
            $table->timestamps();
        });

        // 3. Leads (Visitantes registrados en el stand)
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stand_id')->constrained('stands')->onDelete('cascade');
            $table->string('full_name', 150);
            $table->string('phone', 50);
            $table->string('city', 100);
            $table->string('profile', 60);
            $table->string('hectares', 60)->nullable();
            $table->string('interest', 120)->nullable();
            $table->jsonb('custom_answers')->nullable(); // Respuestas a preguntas custom del stand
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
        });

        // 4. Ruleta Spins (Giros y Cupones Antifraude)
        Schema::create('ruleta_spins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stand_id')->constrained('stands')->onDelete('cascade');
            $table->foreignId('lead_id')->constrained('leads')->onDelete('cascade');
            $table->foreignId('prize_id')->constrained('prizes')->onDelete('cascade');
            $table->string('coupon_code', 30)->unique();
            $table->boolean('is_redeemed')->default(false);
            $table->timestamp('redeemed_at')->nullable();
            $table->timestamps();
        });

        // 5. Draws (Sorteos en Pantalla TV 4K)
        Schema::create('draws', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stand_id')->constrained('stands')->onDelete('cascade');
            $table->string('title', 150);
            $table->string('prize_description', 200);
            $table->foreignId('winner_lead_id')->nullable()->constrained('leads')->nullOnDelete();
            $table->timestamp('draw_date')->nullable();
            $table->string('status', 30)->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('draws');
        Schema::dropIfExists('ruleta_spins');
        Schema::dropIfExists('leads');
        Schema::dropIfExists('prizes');
        Schema::dropIfExists('stands');
    }
};
