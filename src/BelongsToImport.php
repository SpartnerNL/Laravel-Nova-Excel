<?php

namespace Maatwebsite\LaravelNovaExcel;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Maatwebsite\LaravelNovaExcel\Models\Import;

trait BelongsToImport
{
    /**
     * @return BelongsTo
     */
    public function import()
    {
        return $this->belongsTo(Import::class);
    }
}
