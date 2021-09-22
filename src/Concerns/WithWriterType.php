<?php

namespace Maatwebsite\LaravelNovaExcel\Concerns;

use Laravel\Nova\Http\Requests\ActionRequest;

trait WithWriterType
{
    /**
     * @var string|null
     */
    protected $writerType;

    /**
     * @param  string|null  $writerType
     * @return $this
     */
    public function withWriterType(string $writerType = null)
    {
        $this->writerType = $writerType;

        return $this;
    }

    /**
     * @return string|null
     */
    protected function getWriterType(): ?string
    {
        return $this->writerType;
    }

    /**
     * @param  ActionRequest  $request
     */
    protected function handleWriterType(ActionRequest $request)
    {
        $fields = $request->resolveFields();

        if ($filename = $fields->get('writer_type')) {
            $this->withWriterType($filename);
        }
    }
}
