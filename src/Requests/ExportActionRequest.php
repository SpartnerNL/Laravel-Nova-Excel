<?php

namespace Maatwebsite\LaravelNovaExcel\Requests;

use Laravel\Nova\Lenses\Lens;
use Laravel\Nova\Http\Requests\ActionRequest;
use Laravel\Nova\Http\Requests\LensActionRequest;

class ExportActionRequest extends ActionRequest
{
    /**
     * @var Lens|null
     */
    protected $lens;

    /**
     * @param bool  $onlyIndexFields
     * @param array $only
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|mixed
     */
    public function toExportQuery(bool $onlyIndexFields = false, array $only = [])
    {
        return $this->toSelectedResourceQuery()->when(!$this->forAllMatchingResources(), function ($query) {
            $query->whereKey(explode(',', $this->resources));
        })->when($onlyIndexFields, function ($query) {
            $query->select($this->indexFields());
        })->when(\count($only) > 0, function ($query) use ($only) {
            $query->select($only);
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
     * @param Lens $lens
     */
    protected function setLens(Lens $lens)
    {
        $this->lens = $lens;
    }

    /**
     * @return array
     */
    protected function indexFields(): array
    {
        $fields = $this->lens
            ? collect($this->lens->fields($this))
            : $this->newResource()->indexFields($this);

        return $fields->map->attribute->unique()->all();
    }
}
