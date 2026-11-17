<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Datos Bancarios Oficiales para Transferencias B2B (SIPAP / Bancos)
    |--------------------------------------------------------------------------
    | Estos datos se presentan a las empresas expositoras al momento de
    | activar sus planes en SotePass Expo.
    */
    'bank' => [
        'beneficiary' => env('EXPO_BANK_BENEFICIARY', 'Gabriel Sotelo'),
        'account_holder' => env('EXPO_BANK_ACCOUNT_HOLDER', env('EXPO_BANK_BENEFICIARY', 'Gabriel Sotelo')),
        'ruc' => env('EXPO_BANK_RUC', '1592463'),
        'bank_name' => env('EXPO_BANK_NAME', 'Banco Sudameris'),
        'account_type' => env('EXPO_BANK_ACCOUNT_TYPE', 'Cta. Cte. Guaraníes'),
        'account_number' => env('EXPO_BANK_ACCOUNT_NUMBER', '8010540 (SIPAP: 0001000008010540)'),
        'sipap_alias' => env('EXPO_BANK_SIPAP_ALIAS', '1592463 (CI)'),
        'whatsapp_support' => env('EXPO_WHATSAPP_SUPPORT', '595981762444'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Prueba / Sandbox Gratuito
    |--------------------------------------------------------------------------
    | Límite de contactos de test que puede registrar un stand en modo demo
    | antes de que se requiera la activación oficial.
    */
    'trial' => [
        'max_test_leads' => (int) env('EXPO_MAX_TRIAL_LEADS', 10),
    ],
];
