<?php

namespace Maatwebsite\LaravelNovaExcel\Http\Controllers;

use Illuminate\Routing\Controller;

class DownloadExcelController extends Controller
{
	public function show()
	{
		return response()->download(request('path'),request('filename'))->deleteFileAfterSend();
	}

}