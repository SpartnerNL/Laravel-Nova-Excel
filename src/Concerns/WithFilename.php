<?php

namespace Maatwebsite\LaravelNovaExcel\Concerns;

use Illuminate\Support\Str;
use Laravel\Nova\Http\Requests\ActionRequest;
use Laravel\Nova\Http\Requests\LensActionRequest;
use Laravel\Nova\Resource;

trait WithFilename
{
    /**
     * @var string|null
     */
    protected $filename;

    /**
     * @param  string|null  $filename
     * @return $this
     */
    public function withFilename(string $filename = null)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * @return string|null
     */
    protected function getFilename(): ?string
    {
        return $this->filename;
    }

    /**
     * @param  ActionRequest  $request
     */
    protected function withDefaultFilename(ActionRequest $request)
    {
        /** @var resource $resource */
        $resource = $request->resource();
        $filename = $resource::uriKey();

        // Append the lens name to the filename
        if ($request instanceof LensActionRequest) {
            $filename .= '-' . $request->lens()->uriKey();
        }

        $this->withFilename($filename . '.' . $this->getDefaultExtension());
    }

    /**
     * @return string
     */
    abstract protected function getDefaultExtension(): string;

    /**
     * @param  ActionRequest  $request
     */
    protected function handleFilename(ActionRequest $request): void
    {
        $fields = $request->resolveFields();

        if ($filename = $fields->get('filename', $this->filename)) {
            if (!Str::contains($filename, '.')) {
                $filename .= '.' . $this->getDefaultExtension();
            }

            $this->withFilename($filename);
        } elseif (!$this->getFilename()) {
            $this->withDefaultFilename($request);
        }
    }
}
