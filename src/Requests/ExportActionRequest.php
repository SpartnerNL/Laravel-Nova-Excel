<?php

namespace Maatwebsite\LaravelNovaExcel\Requests;

use Laravel\Nova\Lenses\Lens;
use Illuminate\Support\Collection;
use Laravel\Nova\Http\Requests\ActionRequest;
use Laravel\Nova\Http\Requests\LensActionRequest;

class ExportActionRequest extends ActionRequest
{
    /**
     * @var Lens|null
     */
    protected $lens;

    /**
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|mixed
     */
    public function toExportQuery()
    {
        return $this->toSelectedResourceQuery()->when(!$this->forAllMatchingResources(), function ($query) {
            $query->whereKey(explode(',', $this->resources));
        });
    }

    /**
     * @param ActionRequest $baseRequest
     *
     * @return ExportActionRequest
     */
    public static function createFromActionRequest(ActionRequest $baseRequest): ExportActionRequest
    {
        $request = static::createFrom($baseRequest);

        if ($baseRequest instanceof LensActionRequest) {
            $request->setLens($baseRequest->lens());
        }

        return $request;
    }

    /**
     * @return array
     */
    public function indexFields(): array
    {
        $fields = $this->lens
            ? new Collection($this->lens->fields($this))
            : $this->newResource()->indexFields($this);

        return $fields->map->attribute->unique()->all();
    }

    /**
     * @param Lens $lens
     */
    protected function setLens(Lens $lens)
    {
        $this->lens = $lens;
    }
}
