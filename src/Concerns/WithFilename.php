<?php

namespace Maatwebsite\LaravelNovaExcel\Concerns;

use Laravel\Nova\Http\Requests\ActionRequest;

trait WithFilename
{
    /**
     * @var string|null
     */
    protected $filename;

    /**
     * @param string|null $filename
     *
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
     * @param ActionRequest $request
     */
    protected function withDefaultFilename(ActionRequest $request)
    {
        $resource = $request->resource();

        $this->withFilename(strtolower($resource::label()) . '.' . $this->getDefaultExtension());
    }

    /**
     * @return string
     */
    abstract protected function getDefaultExtension(): string;

    /**
     * @param ActionRequest $request
     */
    protected function handleFilename(ActionRequest $request): void
    {
        $fields = $request->resolveFields();

        if ($filename = $fields->get('filename')) {
            if (!str_contains($filename, '.')) {
                $filename .= '.' . $this->getDefaultExtension();
            }

            $this->withFilename($filename);
        } elseif (!$this->getFilename()) {
            $this->withDefaultFilename($request);
        }
    }
}