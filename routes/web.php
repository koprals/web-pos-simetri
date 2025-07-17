<?php

use App\Exports\TemplateExport;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
});

Route::get('/download-template', function () {
    return Excel::download(new TemplateExport, 'template.xlsx');
})->name('download-template');
