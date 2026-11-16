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
        'beneficiary' => env('EXPO_BANK_BENEFICIARY', 'Soteweb Tecnología'),
        'account_holder' => env('EXPO_BANK_ACCOUNT_HOLDER', env('EXPO_BANK_BENEFICIARY', 'Soteweb Tecnología')),
        'ruc' => env('EXPO_BANK_RUC', '80098765-4'),
        'bank_name' => env('EXPO_BANK_NAME', 'Banco Itaú Paraguay'),
        'account_type' => env('EXPO_BANK_ACCOUNT_TYPE', 'Cuenta Corriente'),
        'account_number' => env('EXPO_BANK_ACCOUNT_NUMBER', '720012345'),
        'sipap_alias' => env('EXPO_BANK_SIPAP_ALIAS', 'soteweb.expo'),
        'whatsapp_support' => env('EXPO_WHATSAPP_SUPPORT', '595981123456'),
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
