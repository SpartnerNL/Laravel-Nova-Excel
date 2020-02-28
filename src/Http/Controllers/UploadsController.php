<?php

namespace Maatwebsite\LaravelNovaExcel\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Validation\ValidationException;
use Laravel\Nova\Http\Requests\NovaRequest;
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
    public function store(string $resource, NovaRequest $request)
    {
        $validated = $this->validate($request, [
            'file' => 'required|file',
        ]);

        $upload = Upload::forUploadedFile(
            $validated['file'],
            $request->user(),
            $resource
        );

        return response()->json(['result' => 'success', 'upload' => $upload->getKey()]);
    }
}
