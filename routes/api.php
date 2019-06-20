<?php

use Illuminate\Support\Facades\Route;
use Maatwebsite\LaravelNovaExcel\Http\Controllers\ExcelController;

Route::get('download', ExcelController::class . '@download');
Route::post('{resource}/upload', ExcelController::class . '@upload');
