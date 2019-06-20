<?php

use Illuminate\Support\Facades\Route;
use Maatwebsite\LaravelNovaExcel\Http\Controllers\ExcelController;
use Maatwebsite\LaravelNovaExcel\Http\Controllers\PreviewController;
use Maatwebsite\LaravelNovaExcel\Http\Controllers\UploadController;

Route::get('download', [ExcelController::class, 'download']);
Route::get('uploads/{upload}/preview', [PreviewController::class, 'show']);
Route::post('{resource}/upload', [UploadController::class, 'store']);
