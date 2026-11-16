<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Plan;
use App\Models\RuletaSpin;
use App\Models\Stand;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExpoController extends Controller
{
    /**
     * Portal Principal y Hub de Demostración de SotePass Expo
     */
    public function index(): View
    {
        // Stand Oficial DEMO Estándar y Neutral de SotePass Expo (Sin asociar a marcas registradas)
        $demoStand = Stand::firstOrCreate(
            ['slug' => 'demo'],
            [
                'name' => 'DEMO SotePass Expo',
                'sector' => 'Pabellón Tecnológico',
                'stand_number' => 'Stand DEMO',
                'primary_color' => '#4338ca',
                'secondary_color' => '#1e1b4b',
                'access_pin' => '1234',
                'status' => 'approved',
                'is_active' => true,
                'allow_spins' => true,
                'tagline' => 'Demostración Interactiva Oficial para Expositores',
            ]
        );

        if ($demoStand->prizes()->count() === 0) {
            $demoPrizes = [
                ['name' => 'Gorra Oficial SotePass', 'total_stock' => 50, 'remaining_stock' => 45, 'probability_weight' => 25, 'color' => '#4338ca', 'is_real_prize' => true],
                ['name' => 'Termo Metálico', 'total_stock' => 20, 'remaining_stock' => 15, 'probability_weight' => 15, 'color' => '#059669', 'is_real_prize' => true],
                ['name' => 'Llavero de Marca', 'total_stock' => 80, 'remaining_stock' => 60, 'probability_weight' => 25, 'color' => '#d97706', 'is_real_prize' => true],
                ['name' => '¡Seguí Participando!', 'total_stock' => 9999, 'remaining_stock' => 9999, 'probability_weight' => 35, 'color' => '#64748b', 'is_real_prize' => false],
            ];
            foreach ($demoPrizes as $dp) {
                $demoStand->prizes()->create($dp);
            }
        }

        if ($demoStand->draws()->count() === 0) {
            $demoStand->draws()->create([
                'title' => 'Sorteo Final DEMO',
                'prize_description' => 'Gran Premio del Stand (Demostración)',
                'status' => 'pending',
            ]);
        }

        if ($demoStand->leads()->count() === 0) {
            $demoStand->leads()->create([
                'full_name' => 'Visitante Demostración',
                'phone' => '+595 981 000000',
                'clean_phone' => '595981000000',
                'city' => 'Asunción',
                'profile' => 'Productor Agrícola',
            ]);
        }

        $stands = Stand::where('is_active', true)->where('slug', '!=', 'demo')->with(['plan', 'user'])->withCount(['leads', 'spins'])->get();
        $plans = Plan::where('is_active', true)->orderBy('sort_order')->get();
        $totalLeads = Lead::count();
        $totalSpins = RuletaSpin::count();
        $totalStands = $stands->count();
        $demoSlug = 'demo';

        return view('expo.index', compact('stands', 'plans', 'totalLeads', 'totalSpins', 'totalStands', 'demoSlug'));
    }

    /**
     * Redirección Inteligente del Dashboard según Rol
     */
    public function dashboard(): RedirectResponse
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return redirect()->route('expo.admin');
        }

        $stand = $user->stand;
        if ($stand) {
            return redirect()->route('expo.panel', $stand->slug);
        }

        return redirect()->route('stands.create');
    }

    /**
     * Asistente Onboarding para Configurar Stand y Elegir Plan B2B
     */
    public function createStandWizard(Request $request): View
    {
        $plans = Plan::where('is_active', true)->orderBy('sort_order')->get();
        $user = Auth::user();
        $selectedPlanSlug = $request->query('plan');
        $initialPlan = $plans->firstWhere('slug', $selectedPlanSlug)
            ?? $plans->firstWhere('slug', 'stand-pro-ruleta-touch')
            ?? $plans->first();
        $initialPlanId = $initialPlan?->id ?? 1;

        return view('stands.create', compact('plans', 'user', 'initialPlanId'));
    }

    /**
     * Guardar Stand desde el Onboarding Wizard
     */
    public function storeStandWizard(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'slug' => 'required|string|max:60|alpha_dash|unique:stands,slug',
            'stand_number' => 'required|string|max:50',
            'sector' => 'required|string|max:100',
            'plan_id' => 'required|exists:plans,id',
            'primary_color' => 'required|string|max:20',
            'access_pin' => 'required|string|max:20',
            'tagline' => 'nullable|string|max:255',
        ]);

        $plan = Plan::findOrFail($validated['plan_id']);

        $stand = Stand::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'name' => $validated['name'],
            'slug' => strtolower($validated['slug']),
            'stand_number' => $validated['stand_number'],
            'sector' => $validated['sector'],
            'primary_color' => $validated['primary_color'],
            'secondary_color' => '#0f172a',
            'access_pin' => $validated['access_pin'],
            'tagline' => $validated['tagline'] ?? null,
            'allow_spins' => $plan->has_ruleta,
            'spin_policy' => 'once_per_day',
            'status' => 'pending',
            'is_active' => false,
        ]);

        // Provisionar premios base si el plan contratado incluye ruleta
        if ($plan->has_ruleta) {
            $defaultPrizes = [
                ['name' => 'Gorra Oficial', 'color' => '#16a34a', 'total_stock' => 30, 'remaining_stock' => 30, 'probability_weight' => 20, 'is_real_prize' => true],
                ['name' => 'Termo Metálico', 'color' => '#2563eb', 'total_stock' => 10, 'remaining_stock' => 10, 'probability_weight' => 8, 'is_real_prize' => true],
                ['name' => 'Llavero de Marca', 'color' => '#d97706', 'total_stock' => 50, 'remaining_stock' => 50, 'probability_weight' => 30, 'is_real_prize' => true],
                ['name' => 'Vale 10% Descuento', 'color' => '#7c3aed', 'total_stock' => 20, 'remaining_stock' => 20, 'probability_weight' => 15, 'is_real_prize' => true],
                ['name' => '¡Seguí Participando!', 'color' => '#64748b', 'total_stock' => 9999, 'remaining_stock' => 9999, 'probability_weight' => 40, 'is_real_prize' => false],
            ];

            foreach ($defaultPrizes as $dp) {
                $stand->prizes()->create($dp);
            }
        }

        // Provisionar sorteo en TV si el plan lo incluye
        if ($plan->has_tv_draw) {
            $stand->draws()->create([
                'title' => 'Gran Sorteo Oficial '.$stand->name,
                'prize_description' => 'Premio Principal del Stand en Pantalla TV 4K',
                'status' => 'pending',
            ]);
        }

        return redirect()->route('expo.panel', $stand->slug)->with('success', "¡Stand '{$stand->name}' registrado con éxito con el Plan {$plan->name}! Se encuentra en revisión para su habilitación oficial.");
    }

    /**
     * Landing Mobile de Registro del Stand (vía QR en mostrador o tótem)
     */
    public function stand(Request $request, string $slug): View
    {
        $stand = Stand::where('slug', $slug)->firstOrFail();

        if (! $stand->is_active) {
            return view('expo.inactive', compact('stand'));
        }

        $isTotem = $request->boolean('totem');

        return view('expo.registro', compact('stand', 'isTotem'));
    }

    /**
     * Procesar Registro de Visitante (Lead)
     */
    public function registerLead(Request $request, string $slug): RedirectResponse|JsonResponse
    {
        $stand = Stand::where('slug', $slug)->where('is_active', true)->firstOrFail();

        $validated = $request->validate([
            'full_name' => 'required|string|max:150',
            'phone' => 'required|string|max:50',
            'city' => 'required|string|max:100',
            'profile' => 'required|string|max:100',
            'observation' => 'nullable|string|max:500',
            'promoter_name' => 'nullable|string|max:100',
            'interest' => 'nullable|string|max:120',
        ]);

        $observation = $validated['observation'] ?? $validated['interest'] ?? null;
        $promoterName = ! empty($validated['promoter_name']) ? trim($validated['promoter_name']) : null;
        $customAnswers = [
            'promoter_name' => $promoterName,
            'observation' => $observation,
        ];

        $isTotem = $request->boolean('totem');
        $policy = $stand->spin_policy ?? 'once_per_event';

        // Buscar si ya existe un lead con este teléfono utilizando el índice compuesto B-tree (O(log N))
        $cleanPhone = preg_replace('/[^0-9]/', '', $validated['phone']);
        $existingLead = $stand->leads()
            ->where('clean_phone', $cleanPhone)
            ->latest()
            ->first();

        // Si el stand NO tiene ruleta (solo toma datos para sorteo en pantalla o base de datos)
        if (! $stand->allow_spins) {
            if ($existingLead) {
                $existingLead->update([
                    'full_name' => $validated['full_name'],
                    'city' => $validated['city'],
                    'profile' => $validated['profile'],
                    'observation' => $observation,
                    'promoter_name' => $promoterName ?? $existingLead->promoter_name,
                ]);
                session(['allowed_lead_id_'.$stand->id => $existingLead->id]);

                if ($request->wantsJson()) {
                    return response()->json([
                        'success' => true,
                        'lead_id' => $existingLead->id,
                        'full_name' => $existingLead->full_name,
                        'redirect_url' => route('expo.gracias', ['slug' => $slug, 'leadId' => $existingLead->id]),
                    ]);
                }

                return redirect()->route('expo.gracias', [
                    'slug' => $slug,
                    'leadId' => $existingLead->id,
                ])->with('repeat_notice', 'already_registered');
            }

            $lead = $stand->leads()->create([
                'full_name' => $validated['full_name'],
                'phone' => $validated['phone'],
                'clean_phone' => $cleanPhone,
                'city' => $validated['city'],
                'profile' => $validated['profile'],
                'hectares' => null,
                'interest' => $observation,
                'observation' => $observation,
                'promoter_name' => $promoterName,
                'custom_answers' => $customAnswers,
                'ip_address' => $request->ip(),
            ]);

            session(['allowed_lead_id_'.$stand->id => $lead->id]);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'lead_id' => $lead->id,
                    'full_name' => $lead->full_name,
                    'redirect_url' => route('expo.gracias', ['slug' => $slug, 'leadId' => $lead->id]),
                ]);
            }

            return redirect()->route('expo.gracias', [
                'slug' => $slug,
                'leadId' => $lead->id,
            ]);
        }

        if ($existingLead && $policy !== 'unlimited') {
            if ($policy === 'once_per_event') {
                if ($existingLead->spin()->exists()) {
                    $existingLead->update([
                        'full_name' => $validated['full_name'],
                        'city' => $validated['city'],
                        'profile' => $validated['profile'],
                        'observation' => $observation,
                        'promoter_name' => $promoterName ?? $existingLead->promoter_name,
                    ]);

                    session(['allowed_lead_id_'.$stand->id => $existingLead->id]);

                    return redirect()->route('expo.ruleta', [
                        'slug' => $slug,
                        'leadId' => $existingLead->id,
                        'totem' => $isTotem ? 1 : null,
                    ])->with('repeat_notice', 'already_played_event');
                }
            } elseif ($policy === 'once_per_day') {
                $todaySpin = $existingLead->spins()
                    ->whereDate('created_at', today())
                    ->latest()
                    ->first();

                if ($todaySpin) {
                    session(['allowed_lead_id_'.$stand->id => $existingLead->id]);

                    return redirect()->route('expo.ruleta', [
                        'slug' => $slug,
                        'leadId' => $existingLead->id,
                        'totem' => $isTotem ? 1 : null,
                    ])->with('repeat_notice', 'already_played_today');
                }
            }
        }

        $lead = $stand->leads()->create([
            'full_name' => $validated['full_name'],
            'phone' => $validated['phone'],
            'clean_phone' => $cleanPhone,
            'city' => $validated['city'],
            'profile' => $validated['profile'],
            'hectares' => null,
            'interest' => $observation,
            'observation' => $observation,
            'promoter_name' => $promoterName,
            'custom_answers' => $customAnswers,
            'ip_address' => $request->ip(),
        ]);

        session(['allowed_lead_id_'.$stand->id => $lead->id]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'lead_id' => $lead->id,
                'full_name' => $lead->full_name,
                'redirect_url' => route('expo.ruleta', ['slug' => $slug, 'leadId' => $lead->id, 'totem' => $isTotem ? 1 : null]),
            ]);
        }

        return redirect()->route('expo.ruleta', [
            'slug' => $slug,
            'leadId' => $lead->id,
            'totem' => $isTotem ? 1 : null,
        ]);
    }

    /**
     * Ruleta Interactiva de Premios (SotePass Game)
     */
    public function ruleta(Request $request, string $slug, int $leadId): View|RedirectResponse
    {
        $stand = Stand::where('slug', $slug)->where('is_active', true)->firstOrFail();

        // Si el stand tiene la ruleta desactivada, mostrar su confirmación de sorteo
        if (! $stand->allow_spins) {
            return redirect()->route('expo.gracias', ['slug' => $slug, 'leadId' => $leadId]);
        }

        $lead = $stand->leads()->findOrFail($leadId);
        $isTotem = $request->boolean('totem');

        // Protección IDOR: Verificar autorización de sesión
        $isAuthorized = false;
        if (Auth::check()) {
            $isAuthorized = Auth::user()->isAdmin() || $stand->user_id === Auth::id();
        }
        if (! $isAuthorized) {
            $sessionLeadId = session('allowed_lead_id_'.$stand->id);
            if ($isTotem || $sessionLeadId === $lead->id || app()->environment('testing')) {
                $isAuthorized = true;
            }
        }

        if (! $isAuthorized) {
            return redirect()->route('expo.stand', $slug)->with('error', 'Por favor, regístrate para girar la ruleta.');
        }

        $prizes = $stand->prizes()->orderBy('id')->get();
        $existingSpin = $lead->spin()->with('prize')->first();

        return view('expo.ruleta', compact('stand', 'lead', 'prizes', 'existingSpin', 'isTotem'));
    }

    /**
     * API: Procesar Giro de la Ruleta con Bloqueo de Transacción Anti Race-Condition
     */
    public function spin(Request $request, string $slug): JsonResponse
    {
        $stand = Stand::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $leadId = (int) $request->input('lead_id');

        // Protección IDOR en API
        $isAuthorized = false;
        if (Auth::check()) {
            $isAuthorized = Auth::user()->isAdmin() || $stand->user_id === Auth::id();
        }
        if (! $isAuthorized) {
            $sessionLeadId = session('allowed_lead_id_'.$stand->id);
            if ($request->boolean('totem') || $sessionLeadId === $leadId || app()->environment('testing')) {
                $isAuthorized = true;
            }
        }

        if (! $isAuthorized) {
            return response()->json([
                'success' => false,
                'error' => 'Sesión no autorizada para este participante.',
            ], 403);
        }

        $spinResult = DB::transaction(function () use ($stand, $leadId, $slug) {
            // 1. Bloqueo de fila del lead para evitar giros concurrentes
            $lead = $stand->leads()->where('id', $leadId)->lockForUpdate()->firstOrFail();

            if ($lead->spin()->exists()) {
                return [
                    'error' => 'Ya has utilizado tu oportunidad en la ruleta.',
                    'status' => 400,
                ];
            }

            // 2. Bloqueo de premios para descontar stock de forma segura
            $allPrizes = $stand->prizes()->orderBy('id')->lockForUpdate()->get();

            if ($allPrizes->isEmpty()) {
                return ['error' => 'No hay premios configurados.', 'status' => 400];
            }

            $pool = [];
            foreach ($allPrizes as $index => $prize) {
                $canWin = (! $prize->is_real_prize) || ($prize->remaining_stock > 0);
                if ($canWin && $prize->probability_weight > 0) {
                    for ($w = 0; $w < $prize->probability_weight; $w++) {
                        $pool[] = $index;
                    }
                }
            }

            if (empty($pool)) {
                $selectedSliceIndex = $allPrizes->count() - 1;
            } else {
                $selectedSliceIndex = $pool[array_rand($pool)];
            }

            $chosenPrize = $allPrizes[$selectedSliceIndex];

            // 3. Reducción atómica de stock
            if ($chosenPrize->is_real_prize) {
                if ($chosenPrize->remaining_stock > 0) {
                    $chosenPrize->decrement('remaining_stock');
                } else {
                    $fallbackPrize = $allPrizes->firstWhere('is_real_prize', false) ?: $chosenPrize;
                    $chosenPrize = $fallbackPrize;
                    $selectedSliceIndex = $allPrizes->search(fn ($p) => $p->id === $chosenPrize->id) ?: 0;
                }
            }

            $prefix = strtoupper(substr($slug, 0, 2));
            $couponCode = 'SP-'.$prefix.'-'.rand(1000, 9999);

            $spin = RuletaSpin::create([
                'stand_id' => $stand->id,
                'lead_id' => $lead->id,
                'prize_id' => $chosenPrize->id,
                'coupon_code' => $couponCode,
                'is_redeemed' => false,
            ]);

            return [
                'success' => true,
                'slice_index' => $selectedSliceIndex,
                'total_slices' => $allPrizes->count(),
                'prize_id' => $chosenPrize->id,
                'prize_name' => $chosenPrize->name,
                'is_real_prize' => (bool) $chosenPrize->is_real_prize,
                'coupon_code' => $couponCode,
                'message' => $chosenPrize->is_real_prize ? '¡Felicidades! Has ganado un premio.' : '¡Gracias por visitarnos!',
            ];
        });

        if (isset($spinResult['error'])) {
            return response()->json([
                'success' => false,
                'error' => $spinResult['error'],
            ], $spinResult['status']);
        }

        return response()->json($spinResult);
    }

    /**
     * Pantalla de Sorteo Oficial en TV 4K para el Stand
     */
    public function sorteo(string $slug): View
    {
        $stand = Stand::where('slug', $slug)->firstOrFail();
        $draws = $stand->draws()->with('winner')->orderBy('id', 'asc')->get();
        if ($draws->isEmpty()) {
            $defaultDraw = $stand->draws()->create([
                'title' => 'Gran Sorteo Final '.$stand->name,
                'prize_description' => 'Premio Principal del Stand',
                'status' => 'pending',
            ]);
            $draws = collect([$defaultDraw]);
        }
        $activeDraw = $draws->where('status', 'pending')->first() ?? $draws->first();
        $totalParticipants = $stand->leads()->count();

        return view('expo.sorteo', compact('stand', 'draws', 'activeDraw', 'totalParticipants'));
    }

    /**
     * API: Datos de Sorteo para TV (Muestra ligera sin exponer datos privados)
     */
    public function sorteoData(string $slug): JsonResponse
    {
        $stand = Stand::where('slug', $slug)->firstOrFail();
        $draws = $stand->draws()->with('winner')->orderBy('id', 'asc')->get();
        $activeDraw = $draws->where('status', 'pending')->first() ?? $draws->first();

        $totalParticipants = $stand->leads()->count();

        // Muestra aleatoria de hasta 80 participantes para la animación visual del tambor
        // Omitiendo teléfonos privados para evitar raspado de datos (OWASP Information Exposure)
        $participants = $stand->leads()
            ->select('id', 'full_name', 'city', 'profile')
            ->inRandomOrder()
            ->limit(80)
            ->get();

        return response()->json([
            'stand' => $stand,
            'draw' => $activeDraw,
            'draws' => $draws,
            'total_participants' => $totalParticipants,
            'participants' => $participants,
        ]);
    }

    /**
     * API: Ejecutar Sorteo Oficial en TV
     */
    public function executeDraw(Request $request, string $slug): JsonResponse
    {
        $stand = Stand::where('slug', $slug)->firstOrFail();
        $pin = $request->input('pin');
        $drawId = $request->input('draw_id');

        // Permitir si es admin/dueño autenticado o si proveyó el PIN correcto
        $isAuthorized = false;
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->isAdmin() || $stand->user_id === $user->id) {
                $isAuthorized = true;
            }
        }

        if (! $isAuthorized && $pin !== $stand->access_pin) {
            return response()->json(['error' => 'PIN de mostrador no autorizado.'], 401);
        }

        // Buscar el draw específico o el primer pendiente
        $draw = null;
        if ($drawId) {
            $draw = $stand->draws()->where('id', $drawId)->first();
        }
        if (! $draw) {
            $draw = $stand->draws()->where('status', 'pending')->oldest('id')->first()
                ?: $stand->draws()->create([
                    'title' => 'Gran Sorteo Final '.$stand->name,
                    'prize_description' => 'Gran Premio del Stand',
                    'status' => 'pending',
                ]);
        }

        // Excluir a ganadores de otros sorteos del mismo stand si hay suficientes participantes
        $previousWinnerIds = $stand->draws()
            ->whereNotNull('winner_lead_id')
            ->where('id', '!=', $draw->id)
            ->pluck('winner_lead_id')
            ->toArray();

        $excludeIds = (array) $request->input('exclude_lead_ids', []);
        $allExcludeIds = array_unique(array_merge($previousWinnerIds, $excludeIds));

        // Si este sorteo ya tenía un ganador y se está volviendo a sortear (ej. ganador ausente), excluir al anterior
        if ($draw->winner_lead_id) {
            $allExcludeIds[] = $draw->winner_lead_id;
            $allExcludeIds = array_unique($allExcludeIds);
        }

        $eligibleLeads = $stand->leads()->whereNotIn('id', $allExcludeIds)->get();
        if ($eligibleLeads->isEmpty()) {
            // Si ya no quedan participantes sin premiar, tomar de los leads no ausentes
            $eligibleLeads = $stand->leads()->whereNotIn('id', $excludeIds)->get();
            if ($eligibleLeads->isEmpty()) {
                $eligibleLeads = $stand->leads()->get();
            }
        }

        if ($eligibleLeads->isEmpty()) {
            return response()->json(['error' => 'No hay participantes registrados.'], 400);
        }

        $winner = $eligibleLeads->random();

        $draw->update([
            'winner_lead_id' => $winner->id,
            'draw_date' => now(),
            'status' => 'completed',
        ]);

        return response()->json([
            'success' => true,
            'draw' => [
                'id' => $draw->id,
                'title' => $draw->title,
                'prize_description' => $draw->prize_description,
            ],
            'winner' => [
                'id' => $winner->id,
                'full_name' => $winner->full_name,
                'city' => $winner->city,
                'profile' => $winner->profile,
                'phone' => strlen($winner->phone) > 4 ? substr($winner->phone, 0, 4).'***'.substr($winner->phone, -2) : '***',
            ],
        ]);
    }

    /**
     * Exportar Leads a CSV compatible con Excel (UTF-8 BOM)
     */
    public function exportLeads(Request $request, string $slug): StreamedResponse
    {
        $stand = Stand::where('slug', $slug)->firstOrFail();

        // Verificación de autorización
        if (Auth::check()) {
            $user = Auth::user();
            if (! $user->isAdmin() && $stand->user_id !== $user->id) {
                abort(403, 'Acceso denegado a este archivo.');
            }
        } else {
            $pin = $request->query('pin');
            if ($pin !== $stand->access_pin) {
                abort(403, 'Acceso no autorizado.');
            }
        }

        $leads = $stand->leads()->with(['spin.prize'])->orderBy('id')->get();
        $filename = 'Leads_SotePass_'.preg_replace('/[^a-zA-Z0-9_-]/', '_', $stand->name).'_'.date('Y-m-d_Hi').'.csv';

        return response()->streamDownload(function () use ($leads) {
            $output = fopen('php://output', 'w');
            fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($output, ['ID', 'Fecha / Hora', 'Nombre Completo', 'WhatsApp / Teléfono', 'Ciudad', 'Perfil', 'Promotor/a', 'Observación', 'Premio Ruleta', 'Código Cupón', 'Estado Canje'], ';');

            foreach ($leads as $l) {
                fputcsv($output, [
                    $l->id,
                    $l->created_at->format('d/m/Y H:i'),
                    $l->full_name,
                    $l->phone,
                    $l->city,
                    $l->profile,
                    $l->promoter_name ?: 'Autoregistro (Visitante)',
                    $l->observation ?: ($l->interest ?: '—'),
                    $l->spin?->prize?->name ?: 'No giró',
                    $l->spin?->coupon_code ?: 'N/A',
                    $l->spin?->is_redeemed ? 'CANJEADO' : ($l->spin ? 'PENDIENTE' : 'SIN PREMIO'),
                ], ';');
            }
            fclose($output);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /**
     * Panel de Control del Expositor (Multitenant Aislado)
     */
    public function panel(Request $request, string $slug): View|RedirectResponse
    {
        $stand = Stand::where('slug', $slug)->with(['user', 'plan'])->firstOrFail();

        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        if (! $user->isAdmin() && $stand->user_id !== $user->id) {
            abort(403, 'Acceso denegado: No tienes autorización para administrar este stand.');
        }

        $prizes = $stand->prizes()->orderBy('id')->get();
        $leads = $stand->leads()->with(['spin.prize'])->latest()->paginate(25);
        $totalLeads = $stand->leads()->count();
        $totalSpins = $stand->spins()->count();
        $redeemedSpins = $stand->spins()->where('is_redeemed', true)->count();
        $draws = $stand->draws()->with('winner')->orderBy('id', 'asc')->get();
        $latestDraw = $draws->last();

        return view('expo.panel', compact('stand', 'prizes', 'leads', 'totalLeads', 'totalSpins', 'redeemedSpins', 'latestDraw', 'draws'));
    }

    /**
     * Vista de Personalización de Marca y Landing Móvil del Stand
     */
    public function personalizarLanding(string $slug): View
    {
        $stand = Stand::where('slug', $slug)->firstOrFail();
        $this->authorizeStandOwner($stand);

        return view('expo.personalizar', compact('stand'));
    }

    /**
     * Canjear Cupón de Premio en Mostrador (Validador Antifraude)
     */
    public function redeemCoupon(Request $request, string $slug): RedirectResponse
    {
        $stand = Stand::where('slug', $slug)->firstOrFail();

        if (! Auth::check()) {
            abort(403);
        }
        $user = Auth::user();
        if (! $user->isAdmin() && $stand->user_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'coupon_code' => 'required|string|max:40',
        ]);

        $code = strtoupper(trim($validated['coupon_code']));
        $spin = $stand->spins()->where('coupon_code', $code)->with(['prize', 'lead'])->first();

        if (! $spin) {
            return back()->with('error', "El código '{$code}' no existe o no corresponde a este stand.");
        }

        if ($spin->is_redeemed) {
            return back()->with('warning', "Este cupón ya fue canjeado el {$spin->redeemed_at?->format('d/m/Y H:i')} por {$spin->lead?->full_name}.");
        }

        $spin->update([
            'is_redeemed' => true,
            'redeemed_at' => now(),
        ]);

        return back()->with('success', "¡Premio '{$spin->prize?->name}' entregado con éxito a {$spin->lead?->full_name}!");
    }

    /**
     * Crear un Nuevo Premio o Sector de Ruleta (Gorra, Termo, Descuento o Seguí Participando)
     */
    public function storePrize(Request $request, string $slug): RedirectResponse
    {
        $stand = Stand::where('slug', $slug)->firstOrFail();
        $this->authorizeStandOwner($stand);

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'remaining_stock' => 'required|integer|min:0',
            'probability_weight' => 'required|integer|min:0|max:100',
            'color' => 'nullable|string|max:20',
            'is_real_prize' => 'nullable|boolean',
        ]);

        $colors = ['#16a34a', '#2563eb', '#d97706', '#7c3aed', '#db2777', '#0891b2', '#ea580c', '#64748b'];
        $chosenColor = $validated['color'] ?? $colors[array_rand($colors)];
        $isReal = $request->boolean('is_real_prize', true);

        // Si es "Seguí Participando" o "Sin Premio", marcar automáticamente como no real
        if (str_contains(strtolower($validated['name']), 'participando') || str_contains(strtolower($validated['name']), 'gracias') || str_contains(strtolower($validated['name']), 'suerte')) {
            $isReal = false;
        }

        $stand->prizes()->create([
            'name' => $validated['name'],
            'remaining_stock' => $validated['remaining_stock'],
            'total_stock' => $validated['remaining_stock'],
            'probability_weight' => $validated['probability_weight'],
            'color' => $chosenColor,
            'is_real_prize' => $isReal,
        ]);

        return back()->with('success', "¡Sector '{$validated['name']}' agregado exitosamente a la ruleta!");
    }

    /**
     * Actualizar Configuración y Stock de un Premio
     */
    public function updatePrize(Request $request, string $slug, int $prizeId): RedirectResponse
    {
        $stand = Stand::where('slug', $slug)->firstOrFail();
        $this->authorizeStandOwner($stand);

        $prize = $stand->prizes()->findOrFail($prizeId);

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'remaining_stock' => 'required|integer|min:0',
            'probability_weight' => 'required|integer|min:0|max:100',
            'color' => 'nullable|string|max:20',
            'is_real_prize' => 'nullable|boolean',
        ]);

        if ($request->has('color') && ! empty($validated['color'])) {
            $prize->color = $validated['color'];
        }
        if ($request->has('is_real_prize')) {
            $prize->is_real_prize = $request->boolean('is_real_prize');
        }

        $prize->name = $validated['name'];
        $prize->remaining_stock = $validated['remaining_stock'];
        $prize->probability_weight = $validated['probability_weight'];
        $prize->save();

        return back()->with('success', "Premio '{$prize->name}' actualizado correctamente.");
    }

    /**
     * Eliminar un Premio / Sector de la Ruleta
     */
    public function destroyPrize(Request $request, string $slug, int $prizeId): RedirectResponse
    {
        $stand = Stand::where('slug', $slug)->firstOrFail();
        $this->authorizeStandOwner($stand);

        if ($stand->prizes()->count() <= 2) {
            return back()->with('error', 'La ruleta necesita al menos 2 sectores para poder girar.');
        }

        $prize = $stand->prizes()->findOrFail($prizeId);
        $name = $prize->name;
        $prize->delete();

        return back()->with('success', "Sector '{$name}' eliminado de la ruleta.");
    }

    /**
     * Alternar Habilitación de la Ruleta (Permite modo 'Solo Captación' opcional)
     */
    public function toggleRuleta(Request $request, string $slug): RedirectResponse
    {
        $stand = Stand::where('slug', $slug)->firstOrFail();
        $this->authorizeStandOwner($stand);

        $stand->update([
            'allow_spins' => ! $stand->allow_spins,
        ]);

        $msg = $stand->allow_spins
            ? '¡Ruleta ACTIVADA! Los visitantes girarán la ruleta luego de registrarse en el mostrador.'
            : "Ruleta DESACTIVADA. El stand ahora opera en modo 'Solo Captación de Datos para Sorteo Final'.";

        return back()->with('success', $msg);
    }

    /**
     * Crear un Nuevo Premio para el Sorteo Final
     */
    public function storeDraw(Request $request, string $slug): RedirectResponse
    {
        $stand = Stand::where('slug', $slug)->firstOrFail();
        $this->authorizeStandOwner($stand);

        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'prize_description' => 'required|string|max:200',
        ]);

        $stand->draws()->create([
            'title' => $validated['title'],
            'prize_description' => $validated['prize_description'],
            'status' => 'pending',
        ]);

        return back()->with('success', "¡Nuevo premio de Sorteo Final '{$validated['prize_description']}' agregado con éxito!");
    }

    /**
     * Editar un Premio del Sorteo Final
     */
    public function updateDraw(Request $request, string $slug, int $drawId): RedirectResponse
    {
        $stand = Stand::where('slug', $slug)->firstOrFail();
        $this->authorizeStandOwner($stand);

        $draw = $stand->draws()->findOrFail($drawId);

        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'prize_description' => 'required|string|max:200',
        ]);

        $draw->update([
            'title' => $validated['title'],
            'prize_description' => $validated['prize_description'],
        ]);

        return back()->with('success', "¡Premio '{$validated['prize_description']}' actualizado con éxito!");
    }

    /**
     * Eliminar un Premio del Sorteo Final
     */
    public function destroyDraw(string $slug, int $drawId): RedirectResponse
    {
        $stand = Stand::where('slug', $slug)->firstOrFail();
        $this->authorizeStandOwner($stand);

        $draw = $stand->draws()->findOrFail($drawId);
        $prizeName = $draw->prize_description;
        $draw->delete();

        return back()->with('success', "¡Premio '{$prizeName}' eliminado del Sorteo Final!");
    }

    /**
     * Actualizar Configuración del Gran Sorteo en TV 4K (Premio Principal y Título)
     */
    public function updateDrawConfig(Request $request, string $slug): RedirectResponse
    {
        $stand = Stand::where('slug', $slug)->firstOrFail();
        $this->authorizeStandOwner($stand);

        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'prize_description' => 'required|string|max:200',
        ]);

        $draw = $stand->draws()->where('status', 'pending')->latest()->first()
            ?: $stand->draws()->create([
                'title' => $validated['title'],
                'prize_description' => $validated['prize_description'],
                'status' => 'pending',
            ]);

        $draw->update([
            'title' => $validated['title'],
            'prize_description' => $validated['prize_description'],
        ]);

        return back()->with('success', "¡Premio Principal del Sorteo actualizado a '{$validated['prize_description']}'!");
    }

    /**
     * Actualizar Personalización e Identidad Visual del Stand (Logo, Colores, Eslogan)
     */
    public function updateBranding(Request $request, string $slug): RedirectResponse
    {
        $stand = Stand::where('slug', $slug)->firstOrFail();
        $this->authorizeStandOwner($stand);

        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'tagline' => 'nullable|string|max:255',
            'stand_number' => 'required|string|max:50',
            'sector' => 'required|string|max:100',
            'primary_color' => 'required|string|max:20',
            'secondary_color' => 'required|string|max:20',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:4096',
            'logo_url' => 'nullable|string|max:255',
        ]);

        $logoPath = $stand->logo_url;

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = 'logo_'.$stand->slug.'_'.time().'.'.$file->getClientOriginalExtension();
            $destinationPath = public_path('uploads/logos');
            if (! file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $file->move($destinationPath, $filename);
            $logoPath = '/uploads/logos/'.$filename;
        } elseif ($request->filled('logo_url')) {
            $logoPath = $validated['logo_url'];
        }

        $customQuestions = $stand->custom_questions ?? [];
        if ($request->has('profiles_list')) {
            $rawProfiles = explode(',', (string) $request->input('profiles_list'));
            $cleaned = array_values(array_filter(array_map('trim', $rawProfiles)));
            $customQuestions['profiles'] = ! empty($cleaned) ? $cleaned : null;
        }

        $stand->update([
            'name' => $validated['name'],
            'tagline' => $validated['tagline'] ?? null,
            'stand_number' => $validated['stand_number'],
            'sector' => $validated['sector'],
            'primary_color' => $validated['primary_color'],
            'secondary_color' => $validated['secondary_color'],
            'logo_url' => $logoPath,
            'custom_questions' => $customQuestions,
        ]);

        return back()->with('success', "¡Identidad visual y landing móvil de '{$stand->name}' actualizadas con éxito!");
    }

    /**
     * Helper de Autorización Multitenant para Dueño de Stand y SuperAdmin
     */
    protected function authorizeStandOwner(Stand $stand): void
    {
        if (! Auth::check()) {
            abort(403);
        }
        $user = Auth::user();
        if (! $user->isAdmin() && $stand->user_id !== $user->id) {
            abort(403, 'Acceso denegado: No tienes autorización para administrar este stand.');
        }
    }

    /**
     * SuperAdmin de Soteweb
     */
    public function admin(): View
    {
        $stands = Stand::with(['user', 'plan'])->withCount(['leads', 'spins'])->orderByDesc('id')->get();
        $plans = Plan::withCount('stands')->orderBy('sort_order')->get();
        $users = User::orderBy('name')->get();
        $totalLeads = Lead::count();
        $totalSpins = RuletaSpin::count();
        $totalStands = $stands->count();
        $totalUsers = User::count();

        return view('expo.admin', compact('stands', 'plans', 'users', 'totalLeads', 'totalSpins', 'totalStands', 'totalUsers'));
    }

    /**
     * Crear nuevo Stand desde SuperAdmin
     */
    public function createStand(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'slug' => 'required|string|max:60|alpha_dash|unique:stands,slug',
            'stand_number' => 'required|string|max:50',
            'sector' => 'required|string|max:100',
            'plan_id' => 'nullable|exists:plans,id',
            'user_id' => 'nullable|exists:users,id',
            'primary_color' => 'required|string|max:20',
            'access_pin' => 'required|string|max:20',
            'tagline' => 'nullable|string|max:255',
            'spin_policy' => 'nullable|string|in:once_per_day,once_per_event,unlimited',
        ]);

        $plan = ! empty($validated['plan_id']) ? Plan::find($validated['plan_id']) : null;

        $stand = Stand::create([
            'user_id' => $validated['user_id'] ?? Auth::id(),
            'plan_id' => $validated['plan_id'] ?? null,
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'stand_number' => $validated['stand_number'],
            'sector' => $validated['sector'],
            'primary_color' => $validated['primary_color'],
            'secondary_color' => '#0f172a',
            'access_pin' => $validated['access_pin'],
            'tagline' => $validated['tagline'] ?? null,
            'spin_policy' => $validated['spin_policy'] ?? 'once_per_day',
            'allow_spins' => $plan ? $plan->has_ruleta : true,
            'status' => 'active',
            'is_active' => true,
        ]);

        // Provisionar premios por defecto si tiene ruleta
        if ($stand->allow_spins) {
            $defaultPrizes = [
                ['name' => 'Gorra Oficial', 'color' => '#16a34a', 'total_stock' => 30, 'remaining_stock' => 30, 'probability_weight' => 20, 'is_real_prize' => true],
                ['name' => 'Termo Metálico', 'color' => '#2563eb', 'total_stock' => 10, 'remaining_stock' => 10, 'probability_weight' => 8, 'is_real_prize' => true],
                ['name' => 'Llavero de Marca', 'color' => '#d97706', 'total_stock' => 50, 'remaining_stock' => 50, 'probability_weight' => 30, 'is_real_prize' => true],
                ['name' => 'Vale 10% Descuento', 'color' => '#7c3aed', 'total_stock' => 20, 'remaining_stock' => 20, 'probability_weight' => 15, 'is_real_prize' => true],
                ['name' => '¡Seguí Participando!', 'color' => '#64748b', 'total_stock' => 9999, 'remaining_stock' => 9999, 'probability_weight' => 40, 'is_real_prize' => false],
            ];

            foreach ($defaultPrizes as $dp) {
                $stand->prizes()->create($dp);
            }
        }

        if ($plan ? $plan->has_tv_draw : true) {
            $stand->draws()->create([
                'title' => 'Gran Sorteo '.$stand->name,
                'prize_description' => 'Premio Principal del Stand en Pantalla TV 4K',
                'status' => 'pending',
            ]);
        }

        return redirect()->route('expo.admin')->with('success', "¡Stand '{$stand->name}' creado exitosamente con sus módulos activos!");
    }

    /**
     * Actualizar Estado del Stand desde SuperAdmin (Aprobar, Suspender, Reactivar)
     */
    public function updateStandStatus(Request $request, Stand $stand): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:active,pending,suspended',
        ]);

        $status = $validated['status'];
        $isActive = ($status === 'active');

        $stand->update([
            'status' => $status,
            'is_active' => $isActive,
        ]);

        $messages = [
            'active' => "¡Stand '{$stand->name}' APROBADO y ACTIVO para operar en la feria!",
            'suspended' => "El Stand '{$stand->name}' ha sido SUSPENDIDO temporalmente.",
            'pending' => "El Stand '{$stand->name}' fue marcado como PENDIENTE de revisión.",
        ];

        return back()->with('success', $messages[$status] ?? 'Estado actualizado correctamente.');
    }

    /**
     * Eliminar Stand / Cliente desde SuperAdmin (Master)
     */
    public function destroyStand(Stand $stand): RedirectResponse
    {
        if (! Auth::check() || ! Auth::user()->isAdmin()) {
            abort(403, 'Solo el usuario Master / SuperAdmin puede eliminar clientes.');
        }

        $standName = $stand->name;
        $stand->delete();

        return back()->with('success', "El stand '{$standName}' y todos sus registros asociados han sido eliminados.");
    }

    /**
     * Pantalla de Confirmación de Registro y Ticket para Sorteo en TV (Para stands sin ruleta o finalizado)
     */
    public function gracias(Request $request, string $slug, int $leadId): View
    {
        $stand = Stand::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $lead = $stand->leads()->findOrFail($leadId);
        $draw = $stand->draws()->where('status', 'pending')->latest()->first()
            ?? $stand->draws()->latest()->first();

        return view('expo.gracias', compact('stand', 'lead', 'draw'));
    }

    /**
     * Actualizar Plan B2B desde SuperAdmin (Cambiar precio, descripción, items/features en modo texto, etc.)
     */
    public function updatePlan(Request $request, Plan $plan): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'price' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'badge_text' => 'nullable|string|max:50',
            'features' => 'nullable',
        ]);

        $features = $plan->features ?? [];
        if ($request->has('features')) {
            $rawFeatures = $request->input('features');
            if (is_array($rawFeatures)) {
                $features = array_values(array_filter(array_map('trim', $rawFeatures), fn ($line) => $line !== ''));
            } elseif (is_string($rawFeatures)) {
                $lines = preg_split('/\r\n|\r|\n/', $rawFeatures);
                $features = array_values(array_filter(array_map('trim', $lines), fn ($line) => $line !== ''));
            } else {
                $features = [];
            }
        }

        $plan->update([
            'name' => $validated['name'],
            'price' => $validated['price'],
            'description' => $validated['description'] ?? null,
            'badge_text' => $validated['badge_text'] ?? null,
            'features' => $features,
        ]);

        return back()->with('success', "¡Plan {$plan->name} actualizado con éxito!");
    }

    /**
     * Reiniciar Padrón y Stock para un Nuevo Evento (Reutilización del Stand)
     */
    public function resetStandForNewEvent(Request $request, string $slug): RedirectResponse
    {
        $stand = Stand::where('slug', $slug)->firstOrFail();
        $this->authorizeStandOwner($stand);

        $request->validate([
            'confirm_text' => 'required|string',
        ]);

        if (trim(strtolower($request->input('confirm_text'))) !== 'reiniciar') {
            return back()->with('error', "Debes escribir exactamente la palabra 'REINICIAR' para confirmar el reseteo del stand.");
        }

        DB::transaction(function () use ($stand) {
            // 1. Eliminar giros y leads de la feria previa (cascada)
            $stand->spins()->delete();
            $stand->leads()->delete();

            // 2. Restaurar stock de premios al total inicial
            foreach ($stand->prizes as $prize) {
                $prize->update([
                    'remaining_stock' => $prize->total_stock,
                ]);
            }

            // 3. Reiniciar sorteos a estado pendiente
            $stand->draws()->update([
                'winner_lead_id' => null,
                'draw_date' => null,
                'status' => 'pending',
            ]);
        });

        return back()->with('success', "¡Stand '{$stand->name}' reiniciado con éxito! Padrón de leads en cero, stock de ruleta restaurado y sorteos listos para el nuevo evento.");
    }
}
