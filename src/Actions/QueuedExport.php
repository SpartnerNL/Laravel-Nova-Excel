<?php

namespace Maatwebsite\LaravelNovaExcel\Actions;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Laravel\Nova\Nova;
use Laravie\SerializesQuery\Eloquent;
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

        if ($this->query instanceof QueryBuilder || $this->query instanceof Relation) {
            $this->query = Eloquent::serialize($this->query);
        }

        return ['headings', 'except', 'only', 'onlyIndexFields', 'request', 'resource', 'query'];
    }

    /**
     * Deserialize the action.
     */
    public function __wakeup()
    {
        if ($this->request instanceof SerializedRequest) {
            $this->request = $this->request->unserialize();
        }

        if (is_array($this->query)) {
            $this->query = Eloquent::unserialize($this->query);
        }

        // Reload Nova resources
        Nova::resourcesIn(app_path('Nova'));
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name ?? __('Export to Excel');
    }
}
