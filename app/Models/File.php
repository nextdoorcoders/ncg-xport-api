<?php

namespace App\Models;

use App\Models\Traits\UuidTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class File
 *
 * @package App\Models
 * @property string  $id
 * @property string  $fileable_type
 * @property string  $fileable_id
 * @property string  $field
 * @property string  $name
 * @property integer $size
 * @property string  $type
 * @property string  $disk_name
 * @property Carbon  $created_at
 * @property Carbon  $updated_at
 */
class File extends Model
{
    use HasFactory, UuidTrait;

    protected $table = 'morph_files';

    protected $fillable = [
        'fileable_type',
        'fileable_id',
        'field',
        'name',
        'size',
        'type',
        'disk_name',
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
}
