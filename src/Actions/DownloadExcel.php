<?php

namespace Maatwebsite\LaravelNovaExcel\Actions;

use Illuminate\Support\Facades\URL;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Http\Requests\ActionRequest;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DownloadExcel extends ExportToExcel
{
    /**
     * Get the displayable name of the action.
     *
     * @return string
     */
    public function name()
    {
        return $this->name ?? __('Download Excel');
    }

    /**
     * @param ActionRequest $request
     * @param Action        $exportable
     *
     * @return array
     */
    public function handle(ActionRequest $request, Action $exportable): array
    {
        $response = Excel::download(
            $exportable,
            $this->getFilename(),
            $this->getWriterType()
        );

        if (!$response instanceof BinaryFileResponse || $response->isInvalid()) {
            return \is_callable($this->onFailure)
                ? ($this->onFailure)($request, $response)
                : Action::danger(__('Resource could not be exported.'));
        }

        return \is_callable($this->onSuccess)
            ? ($this->onSuccess)($request, $response)
            : Action::download(
                $this->getDownloadUrl($response),
                $this->getFilename()
            );
    }

    /**
     * @param BinaryFileResponse $response
     *
     * @return string
     */
    protected function getDownloadUrl(BinaryFileResponse $response): string
    {
        return URL::temporarySignedRoute('laravel-nova-excel.download', now()->addMinutes(1), [
            'path'     => encrypt($response->getFile()->getPathname()),
            'filename' => $this->getFilename(),
        ]);
    }
}
