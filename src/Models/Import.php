<?php

namespace Maatwebsite\LaravelNovaExcel\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property array  mapping
 * @property Upload upload
 */
class Import extends Model
{
    use Resourceable;

    /**
     * @var string
     */
    protected $table = 'excel_imports';

    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'resource',
        'mapping',
        'status',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'mapping' => 'array',
    ];

    /**
     * @param Upload $upload
     * @param array  $mapping
     *
     * @return Import
     */
    public static function fromUpload(Upload $upload, array $mapping): Import
    {
        return DB::transaction(function () use ($upload, $mapping) {
            $import = new static([
                'mapping'  => $mapping,
                'resource' => $upload->resource,
            ]);

            $import->upload()->associate($upload);
            $import->user()->associate($upload->user);
            $import->saveOrFail();

            return $import;
        });
    }

    /**
     * @return BelongsTo
     */
    public function user()
    {
        $guard    = config('nova.guard') ?: config('auth.defaults.guard');
        $provider = config("auth.guards.{$guard}.provider");
        $model    = config("auth.providers.{$provider}.model");

        return $this->belongsTo($model, 'user_id');
    }

    /**
     * @return BelongsTo
     */
    public function upload()
    {
        return $this->belongsTo(Upload::class);
    }
}
