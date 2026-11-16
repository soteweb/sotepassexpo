<?php

namespace Database\Seeders;

use App\Models\Draw;
use App\Models\Plan;
use App\Models\Stand;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Sembrar Planes B2B
        $this->call(PlanSeeder::class);
        $planExpress = Plan::where('slug', 'stand-express-qr')->first();
        $planPro = Plan::where('slug', 'stand-pro-ruleta-touch')->first();
        $planVip = Plan::where('slug', 'stand-corporativo-vip-4k')->first();

        // 2. Usuario SuperAdmin de Soteweb
        $admin = User::updateOrCreate(
            ['email' => 'admin@soteweb.com'],
            [
                'name' => 'SuperAdmin Soteweb',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'phone' => '+595 981 123456',
                'company_name' => 'Soteweb Tecnología',
            ]
        );

        // 3. Usuario Expositor Kurosu & Cia.
        $userKurosu = User::updateOrCreate(
            ['email' => 'kurosu@demo.com'],
            [
                'name' => 'Carlos Benítez (Kurosu)',
                'password' => Hash::make('demo123'),
                'role' => 'expositor',
                'phone' => '0985 100 200',
                'company_name' => 'Kurosu & Cia. | John Deere',
            ]
        );

        // 4. Stand Demo 1: Kurosu & Cia. (John Deere)
        $kurosu = Stand::updateOrCreate(
            ['slug' => 'kurosu'],
            [
                'user_id' => $userKurosu->id,
                'plan_id' => $planVip?->id,
                'status' => 'active',
                'name' => 'Kurosu & Cia. | John Deere',
                'tagline' => 'Líderes en tecnología agrícola y soluciones de campo',
                'stand_number' => 'Stand 45',
                'sector' => 'Sector Maquinarias',
                'primary_color' => '#16a34a',
                'secondary_color' => '#0f172a',
                'logo_url' => '/images/stands/kurosu.svg',
                'access_pin' => '1234',
                'allow_spins' => true,
                'max_spins_per_lead' => 1,
                'spin_policy' => 'once_per_day',
                'custom_questions' => [
                    ['id' => 'interes_maquinaria', 'label' => '¿Qué maquinaria le interesa?', 'type' => 'select', 'options' => ['Tractores', 'Cosechadoras', 'Pulverizadoras', 'Repuestos']],
                ],
                'is_active' => true,
            ]
        );

        if ($kurosu->prizes()->count() === 0) {
            $prizesKurosu = [
                ['name' => 'Gorra Oficial John Deere', 'color' => '#15803d', 'text_color' => '#ffffff', 'total_stock' => 30, 'remaining_stock' => 24, 'probability_weight' => 20, 'is_real_prize' => true, 'icon_badge' => 'cap'],
                ['name' => 'Termo Térmico Grabado', 'color' => '#1e40af', 'text_color' => '#ffffff', 'total_stock' => 10, 'remaining_stock' => 6, 'probability_weight' => 8, 'is_real_prize' => true, 'icon_badge' => 'cup'],
                ['name' => 'Llavero Metálico', 'color' => '#d97706', 'text_color' => '#ffffff', 'total_stock' => 50, 'remaining_stock' => 45, 'probability_weight' => 30, 'is_real_prize' => true, 'icon_badge' => 'key'],
                ['name' => 'Vale 15% Descuento Repuestos', 'color' => '#7c3aed', 'text_color' => '#ffffff', 'total_stock' => 20, 'remaining_stock' => 18, 'probability_weight' => 15, 'is_real_prize' => true, 'icon_badge' => 'ticket'],
                ['name' => 'Kit Destornillador Pro', 'color' => '#047857', 'text_color' => '#ffffff', 'total_stock' => 15, 'remaining_stock' => 11, 'probability_weight' => 10, 'is_real_prize' => true, 'icon_badge' => 'tool'],
                ['name' => '¡Seguí Participando!', 'color' => '#64748b', 'text_color' => '#ffffff', 'total_stock' => 9999, 'remaining_stock' => 9999, 'probability_weight' => 40, 'is_real_prize' => false, 'icon_badge' => 'smile'],
            ];

            foreach ($prizesKurosu as $p) {
                $kurosu->prizes()->create($p);
            }
        }

        if ($kurosu->leads()->count() === 0) {
            $leadsKurosu = [
                ['full_name' => 'Hugo Benítez', 'phone' => '0985123456', 'city' => 'Hohenau', 'profile' => 'Productor / Agricultor', 'hectares' => '200 - 500 ha', 'interest' => 'Cosechadoras Serie S'],
                ['full_name' => 'Martina Rojas', 'phone' => '0971987654', 'city' => 'Encarnación', 'profile' => 'Empresa / Distribuidor', 'hectares' => '> 1000 ha', 'interest' => 'Repuestos Originales'],
                ['full_name' => 'Esteban Duarte', 'phone' => '0981555666', 'city' => 'Obligado', 'profile' => 'Productor / Agricultor', 'hectares' => '50 - 200 ha', 'interest' => 'Tractores Serie 5E'],
                ['full_name' => 'Laura Bogado', 'phone' => '0992333444', 'city' => 'Bella Vista', 'profile' => 'Estudiante / Académico', 'hectares' => '< 50 ha', 'interest' => 'Agricultura de Precisión'],
                ['full_name' => 'Carlos Giménez', 'phone' => '0983111222', 'city' => 'Pirapó', 'profile' => 'Productor / Agricultor', 'hectares' => '500 - 1000 ha', 'interest' => 'Sembradoras'],
            ];

            foreach ($leadsKurosu as $l) {
                $kurosu->leads()->create($l);
            }
        }

        if ($kurosu->draws()->count() === 0) {
            Draw::create([
                'stand_id' => $kurosu->id,
                'title' => 'Gran Sorteo Oficial Kurosu 2026',
                'prize_description' => 'Kit de Herramientas Premium John Deere + Mochila Técnica',
                'status' => 'pending',
            ]);
        }

        // 5. Usuario Expositor Somax Agro
        $userSomax = User::updateOrCreate(
            ['email' => 'somax@demo.com'],
            [
                'name' => 'Ing. Fernando Prieto (Somax)',
                'password' => Hash::make('demo123'),
                'role' => 'expositor',
                'phone' => '0971 300 400',
                'company_name' => 'Somax Agro S.A.',
            ]
        );

        // 6. Stand Demo 2: Somax Agro
        $somax = Stand::updateOrCreate(
            ['slug' => 'somax'],
            [
                'user_id' => $userSomax->id,
                'plan_id' => $planPro?->id,
                'status' => 'active',
                'name' => 'Somax Agro S.A.',
                'tagline' => 'Nutrición vegetal y biotecnología aplicada al agro',
                'stand_number' => 'Stand 12',
                'sector' => 'Sector Insumos & Biológicos',
                'primary_color' => '#2563eb',
                'secondary_color' => '#0f172a',
                'logo_url' => '/images/stands/somax.svg',
                'access_pin' => '1234',
                'allow_spins' => true,
                'max_spins_per_lead' => 1,
                'spin_policy' => 'once_per_event',
                'is_active' => true,
            ]
        );

        if ($somax->prizes()->count() === 0) {
            $prizesSomax = [
                ['name' => 'Chaleco Somax Tech', 'color' => '#2563eb', 'text_color' => '#ffffff', 'total_stock' => 20, 'remaining_stock' => 18, 'probability_weight' => 15, 'is_real_prize' => true, 'icon_badge' => 'shirt'],
                ['name' => 'Mochila Impermeable', 'color' => '#0284c7', 'text_color' => '#ffffff', 'total_stock' => 15, 'remaining_stock' => 12, 'probability_weight' => 10, 'is_real_prize' => true, 'icon_badge' => 'bag'],
                ['name' => 'Termo Inox Somax', 'color' => '#4f46e5', 'text_color' => '#ffffff', 'total_stock' => 10, 'remaining_stock' => 8, 'probability_weight' => 8, 'is_real_prize' => true, 'icon_badge' => 'cup'],
                ['name' => 'Gorra Bordada', 'color' => '#10b981', 'text_color' => '#ffffff', 'total_stock' => 40, 'remaining_stock' => 35, 'probability_weight' => 25, 'is_real_prize' => true, 'icon_badge' => 'cap'],
                ['name' => '¡Seguí Participando!', 'color' => '#64748b', 'text_color' => '#ffffff', 'total_stock' => 9999, 'remaining_stock' => 9999, 'probability_weight' => 40, 'is_real_prize' => false, 'icon_badge' => 'smile'],
            ];

            foreach ($prizesSomax as $p) {
                $somax->prizes()->create($p);
            }
        }

        if ($somax->draws()->count() === 0) {
            Draw::create([
                'stand_id' => $somax->id,
                'title' => 'Sorteo Somax: Pulverizadora Mochila a Batería',
                'prize_description' => 'Mochila Pulverizadora de Precisión 20L',
                'status' => 'pending',
            ]);
        }
    }
}
