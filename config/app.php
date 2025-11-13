<?php

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\ServiceProvider;

// --- TAMBAHKAN BLOK INI UNTUK MEMPERBAIKI ERROR EDITOR ---
use App\Providers\AppServiceProvider;
use App\Providers\AuthServiceProvider;
use App\Providers\ViewServiceProvider;
// ----------------------------------------------------

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    */

    'name' => env('APP_NAME', 'Laravel'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    */

    'debug' => (bool) env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    */

    'url' => env('APP_URL', 'http://localhost'),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    */

    'timezone' => 'UTC',

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    */

    'locale' => env('APP_LOCALE', 'en'),

    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),

    'faker_locale' => env('APP_FAKER_LOCALE', 'en_US'),

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    */

    'cipher' => 'AES-256-CBC',

    'key' => env('APP_KEY'),

    'previous_keys' => [
        ...array_filter(
             \explode(',', (string) env('APP_PREVIOUS_KEYS', ''))
        ),
    ],

    /*
    |--------------------------------------------------------------------------
    | Maintenance Mode Driver
    |--------------------------------------------------------------------------
    */

    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store' => env('APP_MAINTENANCE_STORE', 'database'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    */

    'providers' => ServiceProvider::defaultProviders()->merge([
        /*
         * Package Service Providers...
         */
        SimpleSoftwareIO\QrCode\QrCodeServiceProvider::class, // <-- PENAMBAHAN UNTUK QR CODE
        Milon\Barcode\BarcodeServiceProvider::class, // <-- PENAMBAHAN UNTUK BARCODE

        /*
         * Application Service Providers...
         */
        // PERBAIKAN: Gunakan nama kelas pendek karena sudah di-import di atas
        AppServiceProvider::class,
        AuthServiceProvider::class,
        // App\Providers\BroadcastServiceProvider::class,
        // App\Providers\EventServiceProvider::class, // Diberi komentar di kode Anda
        // RouteServiceProvider is not used in Laravel 11/12

        // --- PENAMBAHAN UNTUK NOTIFIKASI ---
        ViewServiceProvider::class, // Tanda merah di sini seharusnya hilang

    ])->toArray(),

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    */

    'aliases' => Facade::defaultAliases()->merge([
        // 'Example' => App\Facades\Example::class,
        'QrCode' => SimpleSoftwareIO\QrCode\Facades\QrCode::class, // <-- PENAMBAHAN UNTUK QR CODE
        'DNS1D'  => Milon\Barcode\Facades\DNS1DFacade::class, // <-- PENAMBAHAN UNTUK BARCODE 1D
        'DNS2D'  => Milon\Barcode\Facades\DNS2DFacade::class, // <-- PENAMBAHAN UNTUK BARCODE 2D
    ])->toArray(),

];