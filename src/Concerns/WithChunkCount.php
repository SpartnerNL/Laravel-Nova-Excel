<?php

namespace Maatwebsite\LaravelNovaExcel\Concerns;

trait WithChunkCount
{
    /**
     * @param  int  $chunkCount
     * @return $this
     */
    public function withChunkCount(int $chunkCount)
    {
        static::$chunkCount = $chunkCount;

        return $this;
    }

    /**
     * @return int
     */
    public function chunkSize(): int
    {
        return static::$chunkCount;
    }
}
