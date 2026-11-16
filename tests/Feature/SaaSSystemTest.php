<?php

namespace Tests\Feature;

use App\Models\Plan;
use App\Models\Stand;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SaaSSystemTest extends TestCase
{
    public function test_expositor_can_register_and_is_redirected_to_onboarding_wizard(): void
    {
        $uniqueEmail = 'new_expositor_'.rand(1000, 9999).'@agrotest.com';

        $response = $this->post('/register', [
            'name' => 'Carlos Gerente',
            'company_name' => 'Agroquímica del Este',
            'email' => $uniqueEmail,
            'phone' => '0981999888',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $this->assertAuthenticated();
        $user = User::where('email', $uniqueEmail)->firstOrFail();
        $this->assertEquals('expositor', $user->role);
        $this->assertEquals('Agroquímica del Este', $user->company_name);

        $response->assertRedirect(route('stands.create'));
    }

    public function test_expositor_can_onboard_stand_with_plan_and_reach_panel(): void
    {
        $uniqueEmail = 'onboard_user_'.rand(1000, 9999).'@agrotest.com';
        $user = User::create([
            'name' => 'María Directora',
            'company_name' => 'Semillas Guaraní',
            'email' => $uniqueEmail,
            'phone' => '0983111222',
            'role' => 'expositor',
            'password' => Hash::make('secret123'),
        ]);

        $plan = Plan::where('has_ruleta', true)->firstOrFail();
        $slug = 'semillas-guarani-'.rand(100, 999);

        $response = $this->actingAs($user)->post('/stands', [
            'plan_id' => $plan->id,
            'name' => 'Semillas Guaraní S.A.',
            'slug' => $slug,
            'stand_number' => 'Stand B-12',
            'sector' => 'Semillas y Biotecnología',
            'primary_color' => '#15803d',
            'access_pin' => '7788',
            'tagline' => 'La mejor semilla del país',
        ]);

        $stand = Stand::where('slug', $slug)->firstOrFail();
        $this->assertEquals($user->id, $stand->user_id);
        $this->assertEquals($plan->id, $stand->plan_id);
        $this->assertEquals('pending', $stand->status);
        $this->assertFalse($stand->is_active);
        $this->assertTrue($stand->allow_spins);
        $this->assertGreaterThan(0, $stand->prizes()->count());

        $response->assertRedirect(route('expo.panel', $slug));

        // El usuario puede acceder a su panel
        $panelResponse = $this->actingAs($user)->get(route('expo.panel', $slug));
        $panelResponse->assertStatus(200);
        $panelResponse->assertSee('Semillas Guaraní S.A.');
    }

    public function test_multitenant_isolation_prevents_unauthorized_stand_access(): void
    {
        $userA = User::create([
            'name' => 'User A',
            'email' => 'user_a_'.rand(1000, 9999).'@test.com',
            'role' => 'expositor',
            'password' => Hash::make('secret123'),
        ]);

        $userB = User::create([
            'name' => 'User B',
            'email' => 'user_b_'.rand(1000, 9999).'@test.com',
            'role' => 'expositor',
            'password' => Hash::make('secret123'),
        ]);

        $slugA = 'stand-a-'.rand(100, 999);
        $standA = Stand::create([
            'user_id' => $userA->id,
            'name' => 'Stand A',
            'slug' => $slugA,
            'stand_number' => 'A-01',
            'sector' => 'Agro',
            'primary_color' => '#2563eb',
            'secondary_color' => '#0f172a',
            'access_pin' => '1111',
            'is_active' => true,
        ]);

        // User B intenta acceder al panel de Stand A -> debe recibir 403 Forbidden
        $response = $this->actingAs($userB)->get(route('expo.panel', $slugA));
        $response->assertStatus(403);

        // User B intenta acceder al área global /admin -> es rechazado y redirigido al dashboard con error
        $adminResponse = $this->actingAs($userB)->get('/admin');
        $adminResponse->assertRedirect(route('dashboard'));

        // Con petición JSON debe recibir 403 Forbidden
        $adminJsonResponse = $this->actingAs($userB)->getJson('/admin');
        $adminJsonResponse->assertStatus(403);
    }

    public function test_admin_can_access_admin_panel_and_any_stand(): void
    {
        $admin = User::where('role', 'admin')->first() ?? User::create([
            'name' => 'Super Admin',
            'email' => 'admin_test_'.rand(1000, 9999).'@soteweb.com',
            'role' => 'admin',
            'password' => Hash::make('secret123'),
        ]);

        $stand = Stand::first();

        // Admin accede a /admin
        $this->actingAs($admin)->get('/admin')->assertStatus(200)->assertSee('SuperAdmin');

        // Admin accede a cualquier stand
        if ($stand) {
            $this->actingAs($admin)->get(route('expo.panel', $stand->slug))->assertStatus(200);
        }
    }

    public function test_unauthenticated_user_cannot_access_panel_or_admin(): void
    {
        $this->get('/admin')->assertRedirect(route('login'));

        $stand = Stand::first();
        if ($stand) {
            $this->get(route('expo.panel', $stand->slug))->assertRedirect(route('login'));
        }
    }

    public function test_superadmin_can_approve_and_suspend_stand(): void
    {
        $admin = User::where('role', 'admin')->first() ?? User::create([
            'name' => 'Admin Test',
            'email' => 'admin_status_'.rand(100, 999).'@soteweb.com',
            'role' => 'admin',
            'password' => Hash::make('secret123'),
        ]);

        $stand = Stand::create([
            'name' => 'Stand Pendiente Test',
            'slug' => 'stand-pending-'.rand(100, 999),
            'stand_number' => 'P-01',
            'sector' => 'Test',
            'primary_color' => '#16a34a',
            'secondary_color' => '#0f172a',
            'access_pin' => '9999',
            'status' => 'pending',
            'is_active' => false,
        ]);

        // 1. SuperAdmin aprueba el stand
        $approveResponse = $this->actingAs($admin)->post(route('expo.admin.stand.status', $stand), [
            'status' => 'active',
        ]);
        $approveResponse->assertRedirect();
        $stand->refresh();
        $this->assertEquals('active', $stand->status);
        $this->assertTrue($stand->is_active);

        // 2. SuperAdmin suspende el stand
        $suspendResponse = $this->actingAs($admin)->post(route('expo.admin.stand.status', $stand), [
            'status' => 'suspended',
        ]);
        $suspendResponse->assertRedirect();
        $stand->refresh();
        $this->assertEquals('suspended', $stand->status);
        $this->assertFalse($stand->is_active);

        // 3. Un usuario no admin es redirigido con acceso denegado
        $normalUser = User::create([
            'name' => 'Normal User',
            'email' => 'normal_status_'.rand(100, 999).'@test.com',
            'role' => 'expositor',
            'password' => Hash::make('secret123'),
        ]);
        $unauthResponse = $this->actingAs($normalUser)->post(route('expo.admin.stand.status', $stand), [
            'status' => 'active',
        ]);
        $unauthResponse->assertRedirect(route('dashboard'));
    }

    public function test_stand_without_ruleta_redirects_to_gracias_page_with_ticket(): void
    {
        $slug = 'stand-qr-only-'.rand(100, 999);
        $stand = Stand::create([
            'name' => 'Stand Sin Ruleta',
            'slug' => $slug,
            'stand_number' => 'QR-01',
            'sector' => 'Maquinarias',
            'primary_color' => '#16a34a',
            'secondary_color' => '#0f172a',
            'access_pin' => '1234',
            'allow_spins' => false, // Ruleta Desactivada
            'status' => 'active',
            'is_active' => true,
        ]);

        $uniquePhone = '0981'.rand(100000, 999999);
        $response = $this->post("/stand/{$slug}/registro", [
            'full_name' => 'Visitante Sin Ruleta',
            'phone' => $uniquePhone,
            'city' => 'Santa Rita',
            'profile' => 'Productor / Agricultor',
        ]);

        $lead = $stand->leads()->where('phone', $uniquePhone)->firstOrFail();
        $response->assertRedirect(route('expo.gracias', ['slug' => $slug, 'leadId' => $lead->id]));

        // Probar que la vista de gracias carga correctamente
        $graciasResponse = $this->get(route('expo.gracias', ['slug' => $slug, 'leadId' => $lead->id]));
        $graciasResponse->assertStatus(200);
        $graciasResponse->assertSee('¡Gracias por tu Visita!');
        $graciasResponse->assertSee('Visitante Sin Ruleta');
        $graciasResponse->assertSee('TICKET-');
    }

    public function test_superadmin_can_update_plan_pricing(): void
    {
        $admin = User::where('role', 'admin')->first() ?? User::create([
            'name' => 'Admin Plan Test',
            'email' => 'admin_plan_'.rand(100, 999).'@soteweb.com',
            'role' => 'admin',
            'password' => Hash::make('secret123'),
        ]);

        $plan = Plan::firstOrFail();

        $response = $this->actingAs($admin)->post(route('expo.admin.plans.update', $plan), [
            'name' => 'Plan Ajustado SuperAdmin',
            'price' => 750000,
            'description' => 'Tarifa económica especial para la feria',
            'badge_text' => 'OFERTA ESPECIAL',
        ]);

        $response->assertRedirect();
        $plan->refresh();
        $this->assertEquals(750000, $plan->price);
        $this->assertEquals('Plan Ajustado SuperAdmin', $plan->name);
        $this->assertEquals('OFERTA ESPECIAL', $plan->badge_text);
    }

    public function test_stand_owner_can_manage_prizes_toggle_ruleta_and_update_draw_config(): void
    {
        $user = User::create([
            'name' => 'Owner Custom',
            'email' => 'owner_custom_'.rand(100, 999).'@test.com',
            'role' => 'expositor',
            'password' => Hash::make('secret123'),
        ]);

        $slug = 'custom-stand-'.rand(100, 999);
        $stand = Stand::create([
            'user_id' => $user->id,
            'name' => 'Custom Stand',
            'slug' => $slug,
            'stand_number' => 'C-01',
            'sector' => 'Agro',
            'primary_color' => '#16a34a',
            'secondary_color' => '#0f172a',
            'access_pin' => '1234',
            'allow_spins' => true,
            'status' => 'active',
            'is_active' => true,
        ]);

        // Crear 3 premios iniciales
        $prize1 = $stand->prizes()->create(['name' => 'Gorra', 'total_stock' => 10, 'remaining_stock' => 10, 'probability_weight' => 30]);
        $prize2 = $stand->prizes()->create(['name' => 'Termo', 'total_stock' => 5, 'remaining_stock' => 5, 'probability_weight' => 20]);
        $prize3 = $stand->prizes()->create(['name' => '¡Seguí Participando!', 'total_stock' => 9999, 'remaining_stock' => 9999, 'probability_weight' => 50, 'is_real_prize' => false]);

        // 1. Agregar nuevo premio
        $this->actingAs($user)->post(route('expo.prize.store', $slug), [
            'name' => 'Mochila Especial',
            'remaining_stock' => 15,
            'probability_weight' => 10,
            'color' => '#2563eb',
            'is_real_prize' => 1,
        ])->assertRedirect();

        $this->assertDatabaseHas('prizes', [
            'stand_id' => $stand->id,
            'name' => 'Mochila Especial',
            'remaining_stock' => 15,
        ]);

        // 2. Eliminar un premio
        $this->actingAs($user)->delete(route('expo.prize.destroy', ['slug' => $slug, 'prizeId' => $prize1->id]))
            ->assertRedirect();
        $this->assertDatabaseMissing('prizes', ['id' => $prize1->id]);

        // 3. Alternar Ruleta (Activar/Desactivar)
        $this->actingAs($user)->post(route('expo.stand.toggle-ruleta', $slug))->assertRedirect();
        $stand->refresh();
        $this->assertFalse($stand->allow_spins);

        $this->actingAs($user)->post(route('expo.stand.toggle-ruleta', $slug))->assertRedirect();
        $stand->refresh();
        $this->assertTrue($stand->allow_spins);

        // 4. Actualizar Premio Principal del Sorteo TV 4K
        $this->actingAs($user)->post(route('expo.draw.update', $slug), [
            'title' => 'Sorteo Estelar Aniversario',
            'prize_description' => 'Dron Agrícola DJI Phantom Pro',
        ])->assertRedirect();

        $this->assertDatabaseHas('draws', [
            'stand_id' => $stand->id,
            'title' => 'Sorteo Estelar Aniversario',
            'prize_description' => 'Dron Agrícola DJI Phantom Pro',
        ]);
    }

    public function test_stand_owner_can_customize_branding_colors_logo_and_visitor_landing(): void
    {
        $uniqueEmail = 'branding_owner_'.rand(1000, 9999).'@agrotest.com';
        $user = User::create([
            'name' => 'Marcos Branding',
            'company_name' => 'AgroTec Paraguay',
            'email' => $uniqueEmail,
            'role' => 'expositor',
            'password' => Hash::make('secret123'),
        ]);

        $slug = 'agrotec-'.rand(100, 999);
        $stand = Stand::create([
            'user_id' => $user->id,
            'name' => 'AgroTec Original',
            'slug' => $slug,
            'stand_number' => 'Stand A-10',
            'sector' => 'Tecnología Agrícola',
            'primary_color' => '#16a34a',
            'secondary_color' => '#14532d',
            'allow_spins' => true,
            'is_active' => true,
        ]);

        // 1. Dueño actualiza branding e identidad visual
        $response = $this->actingAs($user)->post(route('expo.stand.branding', $slug), [
            'name' => 'AgroTec Premium 2026',
            'tagline' => 'Innovación y Biotecnología para el Campo',
            'stand_number' => 'Stand VIP-01',
            'sector' => 'Semillas & Drones',
            'primary_color' => '#dc2626',
            'secondary_color' => '#7f1d1d',
            'logo_url' => 'https://sotepass.com/logos/agrotec.png',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('stands', [
            'id' => $stand->id,
            'name' => 'AgroTec Premium 2026',
            'tagline' => 'Innovación y Biotecnología para el Campo',
            'stand_number' => 'Stand VIP-01',
            'sector' => 'Semillas & Drones',
            'primary_color' => '#dc2626',
            'secondary_color' => '#7f1d1d',
            'logo_url' => 'https://sotepass.com/logos/agrotec.png',
        ]);

        // 2. Visitar la landing del visitante en modo Ruleta
        $responseRuleta = $this->get(route('expo.stand', $slug));
        $responseRuleta->assertOk();
        $responseRuleta->assertSee('AgroTec Premium 2026');
        $responseRuleta->assertSee('Innovación y Biotecnología para el Campo');
        $responseRuleta->assertSee('#dc2626');
        $responseRuleta->assertSee('Ruleta de Premios Activa');

        // 3. Desactivar ruleta y verificar modo Sorteo Oficial en TV
        $stand->update(['allow_spins' => false]);
        $responseSorteo = $this->get(route('expo.stand', $slug));
        $responseSorteo->assertOk();
        $responseSorteo->assertSee('Gran Sorteo en TV 4K');
        $responseSorteo->assertSee('Obtener Mi Cupón de Sorteo');
    }
}
