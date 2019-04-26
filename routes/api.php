<?php

use Illuminate\Support\Facades\Route;
use Maatwebsite\LaravelNovaExcel\Http\Controllers\ExcelController;

Route::get('download', ExcelController::class . '@download');
Route::post('upload', ExcelController::class . '@upload');
