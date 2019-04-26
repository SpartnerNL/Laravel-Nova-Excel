<?php

namespace Maatwebsite\LaravelNovaExcel\Tools;

use Laravel\Nova\Nova;
use Laravel\Nova\Tool;
use Maatwebsite\LaravelNovaExcel\Resources\Import as ImportResource;
use Maatwebsite\LaravelNovaExcel\Resources\Upload as UploadResource;

class Import extends Tool
{
    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        Nova::script('laravel-nova-excel', __DIR__ . '/../../dist/js/tool.js');
        Nova::resources([
            UploadResource::class,
            ImportResource::class,
        ]);
    }

    /**
     * @return \Illuminate\View\View|string
     */
    public function renderNavigation()
    {
        return view('laravel-nova-excel::navigation');
    }
}
