<?php

namespace Maatwebsite\LaravelNovaExcel\Actions;

use Laravel\Nova\Actions\Action;
use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Http\Requests\ActionRequest;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DownloadExcel extends ExportToExcel
{
    protected $streamDownload = true;

    /**
     * {@inheritdoc}
     */
    public function handle(ActionRequest $request, $response)
    {
        if ($response == false || ($response instanceof BinaryFileResponse && $response->isInvalid())) {
            return Action::danger(__('Resource export could not be downloaded.'));
        }

        return Action::download(
            $this->getDownloadUrl($response),
            $this->getFilename()
        );
    }

    /**
     * @return string
     */
    private function getDownloadUrl(BinaryFileResponse $response)
    {
        if ($this->streamDownload) {
            return url('/nova-vendor/maatwebsite/laravel-nova-excel/download?') . http_build_query([
                    'path'     => $response->getFile()->getPathname(),
                    'filename' => $this->getFilename(),
                ]);
        } else {
            return url(Storage::disk($this->getDisk())->url($this->getFilename()));
        }
    }
}
