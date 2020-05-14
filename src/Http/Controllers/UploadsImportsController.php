<?php

namespace Maatwebsite\LaravelNovaExcel\Http\Controllers;

use Illuminate\Foundation\Bus\PendingDispatch;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Resource;
use Maatwebsite\Excel\Importer;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Validators\ValidationException;
use Maatwebsite\LaravelNovaExcel\Actions\ImportExcel;
use Maatwebsite\LaravelNovaExcel\Models\Import;
use Maatwebsite\LaravelNovaExcel\Models\Upload;

class UploadsImportsController extends Controller
{
    use ValidatesRequests;

    /**
     * @var Import
     */
    protected $import;

    /**
     * @param Upload      $upload
     * @param NovaRequest $request
     * @param Importer    $importer
     *
     * @return JsonResponse
     */
    public function store(Upload $upload, NovaRequest $request, Importer $importer)
    {
        $import = Import::fromUpload(
            $upload,
            $request->input('mapping')
        );

        $import->update([
            'status' => 'running',
        ]);

        try {
            $action = $this->action($import->getResourceInstance(), $request);

            $imported = $importer->import(
                $action->getImportObject($import, $request),
                $import->upload->path,
                $import->upload->disk
            );

            if (is_callable($action->afterCallback)) {
                ($action->afterCallback)(
                    $import,
                    (object) ($request->input('meta', []))
                );
            }
        } catch (ValidationException $e) {
            $import->update([
                'status' => Import::STATUS_FAILED,
            ]);

            return new JsonResponse([
                'message' => __('File could not be imported.'),
                'errors'  => $this->errors($e),
            ], 422);
        }

        return new JsonResponse([
            'status' => ($imported instanceof PendingDispatch)
            ? 'QUEUED'
            : 'OK',
        ]);
    }

    /**
     * @param resource    $resource
     * @param NovaRequest $request
     *
     * @return ImportExcel
     */
    protected function action(Resource $resource, NovaRequest $request)
    {
        $actions = collect($resource->actions($request));
        if ($request->has('action')) {
            $action = (object) $request->input('action');
            return $actions->first(function (Action $a) use ($action) {
                return $a->uriKey() === $action->uriKey;
            });
        } else return $actions
            ->first(function (Action $action) {
                return $action instanceof ImportExcel;
            });
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
