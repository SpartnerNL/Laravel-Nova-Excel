<?php

namespace Maatwebsite\LaravelNovaExcel\Concerns;

use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;

trait WithHeadings
{
    /**
     * @var callable
     */
    protected $headingCallback;

    /**
     * @var array
     */
    protected $headings = [];

    /**
     * @param array|null $headings
     *
     * @return $this
     */
    public function withHeadings(array $headings = null)
    {
        if (\is_array($headings)) {
            $this->headingCallback = function () use ($headings) {
                return $headings;
            };
        } else {
            $this->headingCallback = $this->autoHeading();
        }

        return $this;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return $this->headings;
    }

    /**
     * @param Builder $query
     */
    protected function handleHeadings($query)
    {
        if (\is_callable($this->headingCallback)) {
            $this->headings = ($this->headingCallback)($query);
        }
    }

    /**
     * @return callable
     */
    protected function autoHeading(): callable
    {
        return function ($query) {
            /**
             * @var Builder
             * @var Model   $model
             */
            $model = $query->first();

            if (!$model) {
                return [];
            }

            return array_keys($model->attributesToArray());
        };
    }
}
