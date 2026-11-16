<?php

namespace Tests\Feature;

use App\Models\Lead;
use App\Models\RuletaSpin;
use App\Models\Stand;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SecurityAndPerformanceTest extends TestCase
{
    public function test_access_pin_is_hidden_from_json_and_sorteo_data(): void
    {
        $stand = Stand::where('slug', 'kurosu')->firstOrFail();

        // 1. Stand model serialization debe ocultar access_pin
        $this->assertArrayNotHasKey('access_pin', $stand->toArray());

        // 2. GET /api/sorteo/{slug} no debe exponer access_pin ni teléfonos en texto claro
        $response = $this->getJson("/api/sorteo/{$stand->slug}");
        $response->assertStatus(200);
        $response->assertJsonMissing(['access_pin' => $stand->access_pin]);

        $content = $response->getContent();
        $this->assertStringNotContainsString('"access_pin"', $content);
    }

    public function test_phone_lookup_populates_clean_phone(): void
    {
        $uniquePhone = '+595 (981) '.rand(100, 999).'-'.rand(100, 999);
        $expectedClean = preg_replace('/[^0-9]/', '', $uniquePhone);

        $this->post('/stand/kurosu/registro', [
            'full_name' => 'Agricultor Test',
            'phone' => $uniquePhone,
            'city' => 'Santa Rita',
            'profile' => 'Productor / Agricultor',
        ]);

        $lead = Lead::where('clean_phone', $expectedClean)->latest('id')->first();
        $this->assertNotNull($lead);
        $this->assertEquals($expectedClean, $lead->clean_phone);
    }

    public function test_coupon_redemption_flow_works_for_authorized_owner(): void
    {
        $stand = Stand::where('slug', 'kurosu')->firstOrFail();
        $owner = $stand->user ?? User::where('role', 'admin')->first();

        $lead = $stand->leads()->first() ?? $stand->leads()->create([
            'full_name' => 'Comprador Maquinaria',
            'phone' => '0981999111',
            'clean_phone' => '0981999111',
            'city' => 'Hohenau',
            'profile' => 'Productor',
        ]);

        $prize = $stand->prizes()->first() ?? $stand->prizes()->create([
            'name' => 'Gorra Oficial',
            'total_stock' => 10,
            'remaining_stock' => 10,
            'probability_weight' => 20,
            'is_real_prize' => true,
        ]);

        $couponCode = 'SP-TEST-'.rand(1000, 9999);
        $spin = RuletaSpin::create([
            'stand_id' => $stand->id,
            'lead_id' => $lead->id,
            'prize_id' => $prize->id,
            'coupon_code' => $couponCode,
            'is_redeemed' => false,
        ]);

        // Propietario canjea cupón
        $response = $this->actingAs($owner)->post("/panel/{$stand->slug}/canjear-cupon", [
            'coupon_code' => $couponCode,
        ]);

        $response->assertSessionHas('success');
        $this->assertTrue($spin->fresh()->is_redeemed);

        // Intento de re-canje debe advertir que ya fue canjeado
        $repeatResponse = $this->actingAs($owner)->post("/panel/{$stand->slug}/canjear-cupon", [
            'coupon_code' => $couponCode,
        ]);
        $repeatResponse->assertSessionHas('warning');
    }

    public function test_unauthorized_user_cannot_redeem_coupons_for_another_stand(): void
    {
        $stand = Stand::where('slug', 'kurosu')->firstOrFail();

        $stranger = User::create([
            'name' => 'Stranger User',
            'email' => 'stranger_'.rand(1000, 9999).'@test.com',
            'role' => 'expositor',
            'password' => Hash::make('secret123'),
        ]);

        $response = $this->actingAs($stranger)->post("/panel/{$stand->slug}/canjear-cupon", [
            'coupon_code' => 'SP-ANY-1234',
        ]);

        $response->assertStatus(403);
    }
}
