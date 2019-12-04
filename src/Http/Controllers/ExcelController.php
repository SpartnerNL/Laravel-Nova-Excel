<?php

namespace Maatwebsite\LaravelNovaExcel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Routing\ResponseFactory;
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
     */
    public function download(Request $request, ResponseFactory $response): BinaryFileResponse
    {
        if(!$this->canAccess($request['path'])) {
            abort(404);
        }

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
     * Check if file is valid to download
     *
     * @param string $path
     * @return boolean
     */
    protected function canAccess(string $path):bool
    {
        $isValid = true;
        $validFileExtensions = ['csv', 'xlsx'];

        $path_info = pathinfo($path);
        if(!in_array($path_info['extension'], $validFileExtensions)) {
            $isValid = false;
        }
        return $isValid;
    }
}
