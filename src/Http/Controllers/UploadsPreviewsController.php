<?php

namespace Maatwebsite\LaravelNovaExcel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\LaravelNovaExcel\Models\Upload;
use Maatwebsite\LaravelNovaExcel\Imports\PreviewImport;

class UploadsPreviewsController extends Controller
{
    /**
     * @param Upload      $upload
     * @param NovaRequest $request
     *
     * @return Responsable
     */
    public function show(Upload $upload, NovaRequest $request)
    {
        $import = new PreviewImport($upload, $request);

        Excel::import($import, $upload->path, $upload->disk);

        return $import;
    }
}
