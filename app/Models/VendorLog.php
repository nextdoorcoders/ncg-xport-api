<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;


/**
 * App\Models\VendorLog
 *
 * @property int $id
 * @property string $vendor_service
 * @property int|null $http_code
 * @property string|null $data
 * @property string $message
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read string $time
 * @method static \Illuminate\Database\Eloquent\Builder|VendorLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VendorLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VendorLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|VendorLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VendorLog whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VendorLog whereHttpCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VendorLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VendorLog whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VendorLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VendorLog whereVendorService($value)
 * @mixin Eloquent
 */
class VendorLog extends Model
{
    use HasFactory;

    protected $casts = [
        'data' => 'array'
    ];

    public $fillable=[
        'message',
        'vendor_service',
        'http_code',
        'data'
    ];

    public $appends = ['time'];

    public function getTimeAttribute(): string
    {
        return date('H:i:s d-m-Y', strtotime($this->created_at));
    }

}
