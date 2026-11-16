<?php

namespace Tests\Feature;

use App\Models\Lead;
use App\Models\Stand;
use App\Models\User;
use Tests\TestCase;

class ExpoSystemTest extends TestCase
{
    public function test_hub_page_renders_successfully(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('SotePass');
    }

    public function test_stand_registration_page_renders_successfully(): void
    {
        $response = $this->get('/stand/kurosu');
        $response->assertStatus(200);
        $response->assertSee('Kurosu & Cia.');
    }

    public function test_visitor_can_register_and_spin(): void
    {
        $stand = Stand::where('slug', 'kurosu')->firstOrFail();
        $uniquePhone = '0981'.rand(100000, 999999);

        // 1. Registro
        $response = $this->post('/stand/kurosu/registro', [
            'full_name' => 'Juan Agricultor',
            'phone' => $uniquePhone,
            'city' => 'Hohenau',
            'profile' => 'Productor / Agricultor',
            'hectares' => '200 a 500 ha',
        ]);

        $lead = Lead::where('phone', $uniquePhone)->latest('id')->firstOrFail();
        $this->assertEquals('Juan Agricultor', $lead->full_name);
        $response->assertRedirect(route('expo.ruleta', ['slug' => 'kurosu', 'leadId' => $lead->id]));

        // 2. Ruleta Page
        $ruletaResponse = $this->get("/ruleta/kurosu/{$lead->id}");
        $ruletaResponse->assertStatus(200);
        $ruletaResponse->assertSee('Juan Agricultor');

        // 3. API Spin
        $spinResponse = $this->postJson('/api/spin/kurosu', [
            'lead_id' => $lead->id,
        ]);

        $spinResponse->assertStatus(200);
        $spinResponse->assertJsonStructure([
            'success',
            'slice_index',
            'total_slices',
            'prize_id',
            'prize_name',
            'coupon_code',
        ]);
        $this->assertTrue($spinResponse->json('success'));

        // 4. Intento de re-registro con el mismo teléfono (debe redirigir amablemente y no duplicar giro)
        $repeatResponse = $this->post('/stand/kurosu/registro', [
            'full_name' => 'Juan Agricultor Actualizado',
            'phone' => $uniquePhone,
            'city' => 'Hohenau',
            'profile' => 'Productor / Agricultor',
        ]);

        $repeatResponse->assertRedirect(route('expo.ruleta', ['slug' => 'kurosu', 'leadId' => $lead->id]));
        $this->get(route('expo.ruleta', ['slug' => 'kurosu', 'leadId' => $lead->id]))->assertStatus(200);
    }

    public function test_stand_panel_and_sorteo_render_successfully(): void
    {
        $admin = User::where('role', 'admin')->first() ?? User::factory()->create(['role' => 'admin']);
        $stand = Stand::where('slug', 'kurosu')->firstOrFail();
        $owner = $stand->user ?? $admin;

        // Pantalla pública de sorteo en TV (sin login)
        $this->get('/sorteo/kurosu')->assertStatus(200)->assertSee('Kurosu & Cia.');

        // El propietario autenticado puede acceder a su panel CRM
        $this->actingAs($owner)->get('/panel/kurosu')->assertStatus(200)->assertSee('Kurosu & Cia.');

        // El SuperAdmin puede acceder a la administración global
        $this->actingAs($admin)->get('/admin')->assertStatus(200)->assertSee('SuperAdmin');
    }
}
