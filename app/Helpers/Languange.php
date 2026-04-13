<?php

use Illuminate\Support\Facades\App;

if (!function_exists('current_language')) {
    /**
     * Mengambil kode bahasa yang sedang aktif (contoh: 'pt', 'en', 'tl')
     */
    function current_language()
    {
        return App::getLocale();
    }
}

if (!function_exists('is_language')) {
    /**
     * Cek apakah bahasa tertentu sedang aktif.
     * Berguna untuk class 'active' di Blade.
     * * Usage: {{ is_language('pt') ? 'active' : '' }}
     */
    function is_language($code)
    {
        return App::getLocale() === $code;
    }
}

if (!function_exists('language_name')) {
    /**
     * Mendapatkan nama lengkap bahasa berdasarkan kode
     */
    function language_name($code = null)
    {
        $code = $code ?? current_language();

        $languages = [
            'en' => 'English',
            'pt' => 'Português',
            'tl' => 'Tetun',
            'id' => 'Indonesia',
        ];

        return $languages[$code] ?? strtoupper($code);
    }
}
