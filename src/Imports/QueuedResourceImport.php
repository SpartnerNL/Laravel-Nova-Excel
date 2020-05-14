<?php

namespace Maatwebsite\LaravelNovaExcel\Imports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Laravel\Nova\Http\Requests\NovaRequest;
use Maatwebsite\LaravelNovaExcel\Requests\SerializedRequest;

class QueuedResourceImport extends ResourceImport implements ShouldQueue
{
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
