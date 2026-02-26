<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    /**
     * Switch application locale.
     *
     * @param string $locale
     * @return \Illuminate\Http\RedirectResponse
     */
    public function switch($locale)
    {
        if (in_array($locale, ['id', 'en'])) {
            Session::put('locale', $locale);
        }

        return redirect()->back();
    }
}
