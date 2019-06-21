<?php

namespace Maatwebsite\LaravelNovaExcel\Http\Controllers;

use Illuminate\Routing\Controller;
use Laravel\Nova\Http\Requests\NovaRequest;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\LaravelNovaExcel\Imports\ResourceImport;
use Maatwebsite\LaravelNovaExcel\Models\Import;
use Maatwebsite\LaravelNovaExcel\Models\Upload;
use Illuminate\Foundation\Validation\ValidatesRequests;

class UploadsImportsController extends Controller
{
    use ValidatesRequests;

    /**
     * @param Upload      $upload
     * @param NovaRequest $request
     */
    public function store(Upload $upload, NovaRequest $request)
    {
        $import = Import::fromUpload(
            $upload,
            $request->input('mapping')
        );

        $import->update([
            'status' => 'running',
        ]);

        Excel::import(
            new ResourceImport($import),
            $import->upload->path,
            $import->upload->disk
        );

        $import->update([
            'status' => 'completed',
        ]);
    }
}