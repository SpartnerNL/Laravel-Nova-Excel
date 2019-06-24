<?php

namespace Maatwebsite\LaravelNovaExcel\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Laravel\Nova\Http\Requests\NovaRequest;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Validators\ValidationException;
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
     *
     * @return JsonResponse
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

        try {
            Excel::import(
                new ResourceImport($import, $request),
                $import->upload->path,
                $import->upload->disk
            );

            $import->update([
                'status' => 'completed',
            ]);
        } catch (ValidationException $e) {
            $import->update([
                'status' => 'failed',
            ]);

            return new JsonResponse([
                'message' => __('File could not be imported.'),
                'errors'  => $this->errors($e),
            ], 422);
        }

        return new JsonResponse([
            'status' => 'OK',
        ]);
    }

    /**
     * @param ValidationException $e
     *
     * @return Collection
     */
    private function errors(ValidationException $e)
    {
        return collect($e->failures())->groupBy(function (Failure $failure) {
            return $failure->row();
        })->map(function (Collection $row, $rowNumber) {
            return [
                'row'     => $rowNumber,
                'message' => $row->flatMap->errors()->implode(' '),
            ];
        })->values();
    }
}
