<?php

namespace Maatwebsite\LaravelNovaExcel\Http\Controllers;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Routing\Controller;
use Laravel\Nova\Http\Requests\NovaRequest;
use Maatwebsite\Excel\Importer;
use Maatwebsite\LaravelNovaExcel\Imports\PreviewImport;
use Maatwebsite\LaravelNovaExcel\Models\Upload;

class UploadsPreviewsController extends Controller
{
    /**
     * @param Upload      $upload
     * @param NovaRequest $request
     * @param Importer    $importer
     *
     * @return Responsable
     */
    public function show(Upload $upload, NovaRequest $request, Importer $importer)
    {
        $import = new PreviewImport($upload, $request);

        $importer->import(
            $import,
            $upload->path,
            $upload->disk
        );

        return $import;
    }
}
