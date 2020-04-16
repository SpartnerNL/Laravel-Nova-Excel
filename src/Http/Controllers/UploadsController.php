<?php

namespace Maatwebsite\LaravelNovaExcel\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Validation\ValidationException;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Http\Requests\ActionRequest;
use Laravel\Nova\Nova;
use Maatwebsite\LaravelNovaExcel\Models\Upload;

class UploadsController extends Controller
{
    use ValidatesRequests;

    /**
     * @param string      $resource
     * @param NovaRequest $request
     *
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(ActionRequest $request, string $resource)
    {
        $request->validateFields();

        $actualResource = $this->getResourceName($resource);

        $upload = Upload::forUploadedFile(
            $request->file,
            $request->user(),
            $actualResource !== $resource ? $actualResource : $resource,
            $actualResource !== $resource ? $resource : null
        );

        return response()->json(['result' => 'success', 'upload' => $upload->getKey(), 'meta' => $request->except('file')]);
    }

    private function getResourceName($resource)
    {
        try {
            $model        = Nova::modelInstanceForKey($resource);
            $resourceName = Nova::resourceForModel($model);

            if (class_exists($resourceName) && property_exists($resourceName, 'import_as')) {
                return ($resourceName::$import_as)::uriKey();
            } else return $resource;

        } catch (\Exception $e) {
            return $resource;
        }
    }

}
