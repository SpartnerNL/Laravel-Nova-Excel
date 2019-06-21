<?php

namespace Maatwebsite\LaravelNovaExcel\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeImport;
use Maatwebsite\Excel\Concerns\ToCollection;

class PreviewImport implements ToCollection, WithEvents
{
    /**
     * @var Collection
     */
    public $headings;

    /**
     * @var Collection
     */
    public $rows;

    /**
     * @var int
     */
    public $totalRows = 0;

    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        $this->headings = $collection->shift();

        // TODO: implement a ReadFilter to only read the first 10 rows of the spreadsheet.
        $this->rows = $collection->take(10);
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            BeforeImport::class => function (BeforeImport $event) {
                $this->totalRows = head($event->getReader()->getTotalRows()) - 1;
            },
        ];
    }
}
