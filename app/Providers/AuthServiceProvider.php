<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // \App\Models\Document::class => \App\Policies\DocumentPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        // Admin & Guru boleh mengelola dokumen
        Gate::define('manage-documents', function ($user) {
            return in_array($user->role, ['admin', 'guru']);
        });

        // Gate bawaan untuk admin
        Gate::define('is-admin', function ($user) {
            return $user->role === 'admin';
        });
    }
}
