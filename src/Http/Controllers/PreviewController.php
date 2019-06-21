<?php

namespace Maatwebsite\LaravelNovaExcel\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\LaravelNovaExcel\Models\Upload;
use Maatwebsite\LaravelNovaExcel\Imports\PreviewImport;

class PreviewController extends Controller
{
    /**
     * @param Upload $upload
     *
     * @return JsonResponse
     */
    public function show(Upload $upload)
    {
        $import = new PreviewImport();
        Excel::import($import, $upload->path, $upload->disk);

        return new JsonResponse([
            'totalRows' => $import->totalRows,
            'headings'  => $import->headings,
            'rows'      => $import->rows,
        ]);
    }
}
