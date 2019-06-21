<?php

namespace Maatwebsite\LaravelNovaExcel\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Nova;
use Laravel\Nova\Resource;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\LaravelNovaExcel\Models\Upload;
use Maatwebsite\LaravelNovaExcel\Imports\PreviewImport;

class UploadsPreviewsController extends Controller
{
    /**
     * @param Upload      $upload
     * @param NovaRequest $request
     *
     * @return JsonResponse
     */
    public function show(Upload $upload, NovaRequest $request)
    {
        $import = new PreviewImport();
        Excel::import($import, $upload->path, $upload->disk);

        return new JsonResponse([
            'totalRows' => $import->totalRows,
            'headings'  => $import->headings,
            'rows'      => $import->rows,
            'fields'    => $upload->getResourceInstance()->creationFields($request),
        ]);
    }
}
