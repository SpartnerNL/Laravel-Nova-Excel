<?php

namespace Maatwebsite\LaravelNovaExcel\Http\Controllers;

use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Laravel\Nova\Http\Requests\NovaRequest;
use Maatwebsite\LaravelNovaExcel\Models\Import;
use Maatwebsite\LaravelNovaExcel\Models\Upload;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Maatwebsite\LaravelNovaExcel\Imports\ResourceImport;

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
