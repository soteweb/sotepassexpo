<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExpoController;
use Illuminate\Support\Facades\Route;

// ==========================================
// 1. RUTAS PÚBLICAS & LANDING DE PRESENTACIÓN
// ==========================================
Route::get('/', [ExpoController::class, 'index'])->name('expo.index');

// ==========================================
// 2. AUTENTICACIÓN (LOGIN, REGISTRO, LOGOUT)
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:10,1');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ==========================================
// 3. RUTAS PROTEGIDAS PARA EXPOSITORES (SAAS)
// ==========================================
Route::middleware('auth')->group(function () {
    // Redirección inteligente según rol y stand
    Route::get('/dashboard', [ExpoController::class, 'dashboard'])->name('dashboard');

    // Onboarding Wizard para dar de alta el Stand con su Plan
    Route::get('/stands/create', [ExpoController::class, 'createStandWizard'])->name('stands.create');
    Route::post('/stands', [ExpoController::class, 'storeStandWizard'])->name('stands.store');
    Route::post('/stands/create', [ExpoController::class, 'storeStandWizard']);

    // Panel CRM del Stand (Aislado por empresa / multitenant)
    Route::get('/panel/{slug}', [ExpoController::class, 'panel'])->name('expo.panel');
    Route::post('/panel/{slug}/canjear-cupon', [ExpoController::class, 'redeemCoupon'])->name('expo.redeem');
    Route::post('/panel/{slug}/premios/{prizeId}', [ExpoController::class, 'updatePrize'])->name('expo.prize.update');
    Route::get('/export/{slug}', [ExpoController::class, 'exportLeads'])->name('expo.export');
});

// ==========================================
// 4. SUPERADMIN GLOBAL DE SOTEWEB (EXCLUSIVO)
// ==========================================
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', [ExpoController::class, 'admin'])->name('expo.admin');
    Route::post('/stands', [ExpoController::class, 'createStand'])->name('expo.admin.create');
    Route::post('/stands/{stand}/status', [ExpoController::class, 'updateStandStatus'])->name('expo.admin.stand.status');
    Route::post('/plans/{plan}', [ExpoController::class, 'updatePlan'])->name('expo.admin.plans.update');
});

// ==========================================
// 5. EXPERIENCIAS PÚBLICAS PARA VISITANTES (STAND & JUEGOS)
// ==========================================
// Landing Móvil de Captación (QR del Stand)
Route::get('/stand/{slug}', [ExpoController::class, 'stand'])->name('expo.stand');
Route::post('/stand/{slug}/registro', [ExpoController::class, 'registerLead'])->name('expo.register')->middleware('throttle:30,1');

// Confirmación de Registro y Cupón de Sorteo (Para stands sin ruleta o finalizado)
Route::get('/gracias/{slug}/{leadId}', [ExpoController::class, 'gracias'])->name('expo.gracias');

// Ruleta Digital Touch / Tótem
Route::get('/ruleta/{slug}/{leadId}', [ExpoController::class, 'ruleta'])->name('expo.ruleta');
Route::post('/api/spin/{slug}', [ExpoController::class, 'spin'])->name('expo.api.spin')->middleware('throttle:20,1');

// Pantalla Gigante de Sorteo Oficial en TV 4K
Route::get('/sorteo/{slug}', [ExpoController::class, 'sorteo'])->name('expo.sorteo');
Route::get('/api/sorteo/{slug}', [ExpoController::class, 'sorteoData'])->name('expo.sorteo.data');
Route::post('/api/sorteo/{slug}/ejecutar', [ExpoController::class, 'executeDraw'])->name('expo.sorteo.execute')->middleware('throttle:10,1');
