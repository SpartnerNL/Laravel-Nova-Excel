<?php

namespace Maatwebsite\LaravelNovaExcel\Models;

use Illuminate\Database\Eloquent\Model;

class Import extends Model
{
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
        'status',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        $guard    = config('nova.guard') ?: config('auth.defaults.guard');
        $provider = config("auth.guards.{$guard}.provider");
        $model    = config("auth.providers.{$provider}.model");

        return $this->belongsTo($model, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function upload()
    {
        return $this->belongsTo(Upload::class);
    }
}