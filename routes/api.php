<?php

use Illuminate\Routing\Middleware\ValidateSignature;
use Illuminate\Support\Facades\Route;
use Maatwebsite\LaravelNovaExcel\Http\Controllers\ExcelController;

Route::get('download', [ExcelController::class, 'download'])
    ->name('laravel-nova-excel.download')
    ->middleware(ValidateSignature::class);
