<?php

namespace Maatwebsite\LaravelNovaExcel\Actions;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Actions\Action;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Database\Query\Builder;
use Laravel\Nova\Actions\ActionMethod;
use Maatwebsite\Excel\Concerns\FromQuery;
use Laravel\Nova\Http\Requests\ActionRequest;
use Illuminate\Foundation\Bus\PendingDispatch;
use Maatwebsite\LaravelNovaExcel\Concerns\Only;
use Maatwebsite\Excel\Concerns\WithCustomChunkSize;
use Maatwebsite\LaravelNovaExcel\Concerns\WithDisk;
use Maatwebsite\LaravelNovaExcel\Concerns\WithFilename;
use Maatwebsite\LaravelNovaExcel\Concerns\WithHeadings;
use Maatwebsite\LaravelNovaExcel\Concerns\WithChunkCount;
use Maatwebsite\LaravelNovaExcel\Concerns\WithWriterType;
use Laravel\Nova\Exceptions\MissingActionHandlerException;
use Maatwebsite\LaravelNovaExcel\Interactions\AskForFilename;
use Maatwebsite\LaravelNovaExcel\Requests\ExportActionRequest;
use Maatwebsite\LaravelNovaExcel\Interactions\AskForWriterType;
use Maatwebsite\Excel\Concerns\WithHeadings as WithHeadingsConcern;

class ExportToExcel extends Action implements FromQuery, WithCustomChunkSize, WithHeadingsConcern
{
    use AskForFilename,
        AskForWriterType,
        Only,
        WithChunkCount,
        WithDisk,
        WithFilename,
        WithHeadings,
        WithWriterType;

    /**
     * @var Builder
     */
    protected $query;

    /**
     * @var Field[]
     */
    protected $actionFields;

    /**
     * Remove all attributes from this class when serializing,
     * so the action can be queued as exportable.
     *
     * @return array
     */
    public function __sleep()
    {
        return [];
    }

    /**
     * Execute the action for the given request.
     *
     * @param  \Laravel\Nova\Http\Requests\ActionRequest $request
     *
     * @return mixed
     */
    public function handleRequest(ActionRequest $request)
    {
        $this->handleWriterType($request);
        $this->handleFilename($request);

        $method = ActionMethod::determine($this, $request->targetModel());
        if (!method_exists($this, $method)) {
            throw MissingActionHandlerException::make($this, $method);
        }

        $query = $this->getExportQuery($request);
        $this->handleHeadings($query);

        $response = Excel::store(
            $this->withQuery($query),
            $this->getFilename(),
            $this->getDisk(),
            $this->getWriterType()
        );

        return $this->{$method}($request, $response);
    }

    /**
     * @param ActionRequest        $request
     * @param bool|PendingDispatch $response
     *
     * @return array
     */
    public function handle(ActionRequest $request, $response)
    {
        if (false === $response) {
            return Action::danger(__('Resource could not be exported.'));
        }

        return Action::message(__('Resource was successfully exported.'));
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function withName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Builder
     */
    public function query()
    {
        return $this->query;
    }

    /**
     * @return Field[]
     */
    public function fields()
    {
        return $this->actionFields;
    }

    /**
     * @param Builder $query
     *
     * @return $this
     */
    protected function withQuery($query)
    {
        $this->query = $query;

        return $this;
    }

    /**
     * @return string
     */
    protected function getDefaultExtension(): string
    {
        return $this->getWriterType() ? strtolower($this->getWriterType()) : 'xlsx';
    }

    /**
     * @param ActionRequest $request
     *
     * @return \Illuminate\Database\Eloquent\Builder|Builder|mixed
     */
    private function getExportQuery(ActionRequest $request)
    {
        $query = ExportActionRequest::createFrom($request)->getExportQuery();

        if (\count($this->getOnly()) > 0) {
            $query->select($this->getOnly());
        }

        return $query;
    }
}
