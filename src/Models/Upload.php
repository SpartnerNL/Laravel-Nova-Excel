<?php

namespace Maatwebsite\LaravelNovaExcel\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property string|null $disk
 * @property string $path
 */
class Upload extends Model
{
    /**
     * @var string
     */
    protected $table = 'excel_uploads';

    /**
     * @var array
     */
    protected $fillable = [
        'disk',
        'filename',
        'path',
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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function imports()
    {
        return $this->hasMany(Import::class);
    }

    /**
     * @param UploadedFile $file
     */
    public function linkFile(UploadedFile $file)
    {
        $path = $file->store($this->getFolder(), array_filter([
            'disk' => $this->disk,
        ]));

        $this->update([
            'path' => $path,
        ]);
    }

    /**
     * @return string
     */
    protected function getFolder()
    {
        return 'excel_uploads/'.$this->id;
    }

    /**
     * @inheritdoc
     */
    protected static function boot()
    {
        parent::boot();

        static::deleted(function (Upload $upload) {
            Storage::disk($upload->disk)->delete($upload->path);
        });
    }
}