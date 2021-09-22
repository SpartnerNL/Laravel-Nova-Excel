<?php

namespace Maatwebsite\LaravelNovaExcel\Concerns;

trait WithDisk
{
    /**
     * @var string|null
     */
    protected $disk;

    /**
     * @param  string|null  $disk
     * @return $this
     */
    public function withDisk(string $disk = null)
    {
        $this->disk = $disk;

        return $this;
    }

    /**
     * @return string|null
     */
    protected function getDisk(): ?string
    {
        return $this->disk;
    }
}
