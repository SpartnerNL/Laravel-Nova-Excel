<?php

namespace Maatwebsite\LaravelNovaExcel\Http\Controllers;

use Maatwebsite\Excel\Importer;
use Illuminate\Routing\Controller;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\LaravelNovaExcel\Models\Upload;
use Maatwebsite\LaravelNovaExcel\Imports\PreviewImport;

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

        if (! $upload->stats) {
            $importer->import(
                $import,
                $upload->path,
                $upload->disk
            );
        }

        return $import;
    }
}
