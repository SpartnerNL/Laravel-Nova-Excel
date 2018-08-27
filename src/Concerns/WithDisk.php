<?php

namespace Maatwebsite\LaravelNovaExcel\Concerns;

trait WithDisk
{
    /**
     * @var string|null
     */
    protected $disk;

    protected $streamDownload = false;

    /**
     * @param string|null $disk
     *
     * @return $this
     */
    public function withDisk(string $disk = null)
    {
        $this->disk = $disk;

        $this->streamDownload = false;

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
