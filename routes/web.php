<?php

use App\Exports\TemplateExport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // Cek apakah user sudah login
    if (Auth::check()) {
        // Jika sudah login, redirect ke dashboard Filament
        return redirect()->route('filament.admin.pages.dashboard');
    }

    // Jika belum login, redirect ke halaman login Filament
    return redirect()->route('filament.admin.auth.login');
});

Route::get('/download-template', function () {
    return Excel::download(new TemplateExport, 'template.xlsx');
})->name('download-template');
