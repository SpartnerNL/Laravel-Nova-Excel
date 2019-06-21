<?php

use Illuminate\Support\Facades\Route;
use Maatwebsite\LaravelNovaExcel\Http\Controllers\ExcelController;
use Maatwebsite\LaravelNovaExcel\Http\Controllers\UploadsController;
use Maatwebsite\LaravelNovaExcel\Http\Controllers\UploadsImportsController;
use Maatwebsite\LaravelNovaExcel\Http\Controllers\UploadsPreviewsController;

Route::get('download', [ExcelController::class, 'download']);
Route::post('{resource}/upload', [UploadsController::class, 'store']);
Route::get('uploads/{upload}/preview', [UploadsPreviewsController::class, 'show']);
Route::post('uploads/{upload}/import', [UploadsImportsController::class, 'store']);
