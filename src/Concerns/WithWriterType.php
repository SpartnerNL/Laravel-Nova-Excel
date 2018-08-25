<?php

namespace Maatwebsite\LaravelNovaExcel\Concerns;

trait WithWriterType
{
    /**
     * @var string|null
     */
    protected $writerType;

    /**
     * @param string|null $writerType
     *
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
}