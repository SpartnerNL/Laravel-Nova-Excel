<?php

namespace Maatwebsite\LaravelNovaExcel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Http\Requests\NovaRequest;
use Maatwebsite\LaravelNovaExcel\Models\Import;
use Maatwebsite\LaravelNovaExcel\Models\Upload;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExcelController extends Controller
{
    use ValidatesRequests;

    /**
     * @param Request $request
     * @param ResponseFactory $response
     *
     * @return BinaryFileResponse
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
     * @param NovaRequest $request
     *
     * @throws \Illuminate\Validation\ValidationException
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(NovaRequest $request)
    {
        $this->validate($request, [
            'file' => 'required|file',
        ]);

        /** @var Import $import */
        $import = DB::transaction(function () use ($request) {
            $file = $request->file('file');

            $upload = new Upload([
                'disk'     => null,
                'filename' => $file->getClientOriginalName(),
            ]);

            $upload->user()->associate($request->user());
            $upload->saveOrFail();
            $upload->linkFile($file);

            return $upload->imports()->create([
                'user_id' => $upload->user_id,
                'status'  => 'waiting',
            ]);
        });

        return response()->json(['result' => 'success', 'import' => $import->getKey()]);
    }
}
