<?php

namespace Maatwebsite\LaravelNovaExcel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Routing\ResponseFactory;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Validation\ValidationException;
use Maatwebsite\LaravelNovaExcel\Models\Upload;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExcelController extends Controller
{
    use ValidatesRequests;

    /**
     * @param Request         $request
     * @param ResponseFactory $response
     *
     * @return BinaryFileResponse
     * @throws ValidationException
     */
    public function download(Request $request, ResponseFactory $response): BinaryFileResponse
    {
        $data = $this->validate($request, [
            'path'     => 'required',
            'filename' => 'required',
        ]);

        return $response->download(
            $data['path'],
            $data['filename']
        )->deleteFileAfterSend($shouldDelete = true);
    }

    /**
     * @param string      $resource
     * @param NovaRequest $request
     *
     * @return JsonResponse
     * @throws ValidationException
     */
    public function upload(string $resource, NovaRequest $request)
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
