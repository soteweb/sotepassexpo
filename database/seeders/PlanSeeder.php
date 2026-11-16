<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Stand Express QR',
                'slug' => 'stand-express-qr',
                'description' => 'Ideal para stands medianos que buscan captar contactos rápidos y ordenados sin papel.',
                'price' => 1200000,
                'badge_text' => 'Básico',
                'has_ruleta' => false,
                'has_tv_draw' => false,
                'sort_order' => 1,
                'is_active' => true,
                'features' => [
                    'Landing de registro móvil con logo y colores del stand',
                    'Captura de WhatsApp, Ciudad, Perfil y Hectáreas',
                    'Base de datos en tiempo real (PostgreSQL)',
                    'Descarga de leads en Excel (CSV UTF-8)',
                    'Soporte técnico durante la feria',
                ],
            ],
            [
                'name' => 'Stand Pro Ruleta Touch',
                'slug' => 'stand-pro-ruleta-touch',
                'description' => 'El paquete más elegido. Convierte tu stand en el centro de atracción con la Ruleta de Premios.',
                'price' => 2500000,
                'badge_text' => 'Más Popular',
                'has_ruleta' => true,
                'has_tv_draw' => false,
                'sort_order' => 2,
                'is_active' => true,
                'features' => [
                    'Todo lo del Plan Express',
                    'Ruleta interactiva en vivo (Tablet, TV Touch o Tótem)',
                    'Motor probabilístico con control de stock de premios',
                    'Generación de cupones con código antifraude',
                    'Módulo validador de cupones para promotoras en mostrador',
                    'Políticas de repetición configurables (1 por evento / 1 por día)',
                ],
            ],
            [
                'name' => 'Stand Corporativo VIP 4K',
                'slug' => 'stand-corporativo-vip-4k',
                'description' => 'Para grandes marcas e importadoras que buscan impacto masivo y sorteo en vivo en pantalla gigante.',
                'price' => 4500000,
                'badge_text' => 'Empresas Líderes',
                'has_ruleta' => true,
                'has_tv_draw' => true,
                'sort_order' => 3,
                'is_active' => true,
                'features' => [
                    'Todo lo del Plan Pro Ruleta',
                    'Pantalla de Sorteo Oficial en TV 4K con bombo digital y efectos',
                    'Múltiples promotoras registrando en simultáneo',
                    'Soporte presencial prioritario en el predio',
                    'Exportación segmentada avanzada de base comercial',
                    'Personalización premium de marca (audios y logos)',
                ],
            ],
        ];

        foreach ($plans as $p) {
            Plan::updateOrCreate(['slug' => $p['slug']], $p);
        }
    }
}
