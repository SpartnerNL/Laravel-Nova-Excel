<?php

namespace Maatwebsite\LaravelNovaExcel\Imports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Laravel\Nova\Http\Requests\NovaRequest;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\LaravelNovaExcel\Requests\SerializedRequest;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Events\BeforeImport;
use Maatwebsite\Excel\Events\ImportFailed;
use Maatwebsite\LaravelNovaExcel\Models\Import;

class QueuedResourceImport extends ResourceImport implements ShouldQueue, WithEvents
{


    public function registerEvents(): array
    {
        return [
            ImportFailed::class => function(ImportFailed $event) {
                if (isset($this->import)) $this->import->update([
                    'status' => Import::STATUS_FAILED
                ]);
            },
            BeforeImport::class => function (BeforeImport $event) {
                if (isset($this->import)) $this->import->update([
                    'status' => Import::STATUS_RUNNING
                ]);
            },
            AfterImport::class => function (AfterImport $event) {
                if (isset($this->import)) $this->import->update([
                    'status' => Import::STATUS_COMPLETED
                ]);
            }
        ];
    }

    /**
     * Remove some attributes from this class when serializing,
     * so the import can be queued as exportable.
     *
     * @return array
     */
    public function __sleep()
    {
        $this->request = (object) [
            'query' => $this->request->query,
            'request' => $this->request->request,
            'attributes' => $this->request->attributes,
            'cookies' => $this->request->cookies,
            'files' => $this->request->files,
            'server' => $this->request->server,
            'content' => $this->request->content
        ];

        return [
            'import',
            'meta',
            'matchOn',
            'action',
            'request'
        ];
    }

    /**
     * Deserialize the import.
     */
    public function __wakeup()
    {
        $this->request = new NovaRequest(
            $this->request->query->all(),
            $this->request->request->all(),
            $this->request->attributes->all(),
            $this->request->cookies->all(),
            $this->request->files->all(),
            $this->request->server->all(),
            $this->request->content
        );
    }

    /**
     * @return int
     */
    public function chunkSize(): int
    {
        return 1000;
    }
}
