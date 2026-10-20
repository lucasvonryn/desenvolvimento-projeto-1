<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\MeasurementController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoomController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/empresas');

Route::resource('empresas', CompanyController::class)
    ->parameters(['empresas' => 'empresa']);

Route::prefix('empresas/{empresa}')->name('empresas.')->group(function () {
    Route::resource('comodos', RoomController::class)
        ->parameters(['comodos' => 'comodo'])
        ->except(['show']);

    Route::get('equipamento', [EquipmentController::class, 'edit'])->name('equipamento.edit');
    Route::put('equipamento', [EquipmentController::class, 'update'])->name('equipamento.update');

    Route::resource('medicoes', MeasurementController::class)
        ->parameters(['medicoes' => 'medicao'])
        ->except(['show']);

    Route::get('relatorios', [ReportController::class, 'show'])->name('relatorios.show');
    Route::post('relatorios/pdf', [ReportController::class, 'pdf'])->name('relatorios.pdf');
});
