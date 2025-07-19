<?php

use App\Exports\TemplateExport;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\OrderExportController;
use App\Http\Controllers\RentalCourtExportController;
use App\Http\Controllers\RentalEquipmentExportController;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

Route::get('/', [LandingPageController::class, 'index'])->name('landing');

Route::get('/download-template', function () {
    return Excel::download(new TemplateExport, 'template.xlsx');
})->name('download-template');

Route::get('/orders/export', [OrderExportController::class, 'export'])->name('orders.export');

Route::get('/rental-courts/export', [RentalCourtExportController::class, 'export'])->name('rental-courts.export');

Route::get('/rental-equipments/export', [RentalEquipmentExportController::class, 'export'])->name('rental-equipments.export');
