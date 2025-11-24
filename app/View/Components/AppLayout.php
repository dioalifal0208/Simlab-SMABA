<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    /**
     * Tentukan apakah navigasi & footer ditampilkan.
     * Default: true (hanya disembunyikan untuk halaman-halaman khusus seperti error).
     */
    public function __construct(
        public bool $hideChrome = false,
    ) {
    }

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.app');
    }
}
