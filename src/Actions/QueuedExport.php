<?php

namespace Maatwebsite\LaravelNovaExcel\Actions;

use Illuminate\Contracts\Queue\ShouldQueue;
use Laravel\Nova\Nova;
use Maatwebsite\LaravelNovaExcel\Requests\SerializedRequest;

class QueuedExport extends ExportToExcel implements ShouldQueue
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
        if (!$this->request instanceof SerializedRequest) {
            $this->request = SerializedRequest::serialize($this->request);
        }

        return ['headings', 'except', 'only', 'onlyIndexFields', 'request', 'resource'];
    }

    /**
     * Deserialize the action.
     */
    public function __wakeup()
    {
        if ($this->request instanceof SerializedRequest) {
            $this->request = $this->request->unserialize();
        }

        // Reload Nova resources
        Nova::resourcesIn(app_path('Nova'));
    }

    /**
     * @var string
     */
    public function name()
    {
        return __('Export to Excel');
    }
}
