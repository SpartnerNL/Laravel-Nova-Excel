<?php

namespace Maatwebsite\LaravelNovaExcel\Imports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\LaravelNovaExcel\Requests\SerializedRequest;

class QueuedResourceImport extends ResourceImport implements ShouldQueue
{
    /**
     * Remove some attributes from this class when serializing,
     * so the action can be queued as exportable.
     * Serialize the request, so we keep information about
     * the resource and lens in the queued jobs.
     *
     * @return array
     */
    public function __sleep()
    {
        // if (!$this->request instanceof SerializedRequest) {
        //     $this->request = SerializedRequest::serialize($this->request);
        // }

        return [
            'import',
            'request'
        ];
    }

    /**
     * Deserialize the action.
     */
    public function __wakeup()
    {
        // if ($this->request instanceof SerializedRequest) {
        //     $this->request = $this->request->unserialize();
        // }
    }
}
