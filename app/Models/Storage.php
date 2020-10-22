<?php

namespace App\Models;

use App\Models\Traits\UuidTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage as StorageHelper;

/**
 * Class Storage
 *
 * @package App\Models
 * @property string  $id
 * @property string  $fileable_type
 * @property string  $fileable_id
 * @property string  $name
 * @property string  $desc
 * @property string  $field
 * @property string  $file_name
 * @property string  $file_extension
 * @property string  $file_mime_type
 * @property integer $file_size
 * @property Carbon  $created_at
 * @property Carbon  $updated_at
 */
class Storage extends Model
{
    use HasFactory, UuidTrait;

    protected $table = 'morph_storages';

    protected $fillable = [
        'fileable_type',
        'fileable_id',
        'name',
        'desk',
        'field',
        'file_name',
        'file_extension',
        'file_mime_type',
        'file_size',
    ];

    protected $appends = [
        'url',
    ];

    /**
     * @var array Known image extensions.
     */
    public static $imageExtensions = [
        'jpg',
        'jpeg',
        'png',
        'gif',
        'webp',
    ];

    /*
     * Relations
     */

    /**
     * @return MorphTo
     */
    public function fileable()
    {
        return $this->morphTo('fileable');
    }

    /*
     * Accessors
     */

    /**
     * @return string
     */
    public function getUrlAttribute()
    {
        return StorageHelper::disk('public')->url($this->getPath());
    }

    /*
     * Getters
     */

    /**
     * @return string
     */
    public function getPath()
    {
        return sprintf('%s/%s.%s', $this->getPartitionDirectory(), $this->file_name, $this->file_extension);
    }

    /**
     * Generates a partition for the file.
     * return /ABC/DE1/234 for an name of ABCDE1234.
     *
     * @return mixed
     */
    public function getPartitionDirectory()
    {
        return implode('/', array_slice(str_split($this->file_name, 4), 0, 8));
    }

    /**
     * Checks if the file extension is an image and returns true or false.
     *
     * @return bool
     */
    public function isImage()
    {
        return Arr::has(self::$imageExtensions, $this->file_extension);
    }
}
