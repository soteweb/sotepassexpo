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
}
