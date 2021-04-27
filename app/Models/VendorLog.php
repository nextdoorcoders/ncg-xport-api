<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\VendorsLog
 *
 * @property int $id
 * @property string $vendor_service
 * @property int|null $http_code
 * @property string $message
 * @property \Illuminate\Support\Carbon $created_at
 * @method static \Illuminate\Database\Eloquent\Builder|VendorLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VendorLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VendorLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|VendorLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VendorLog whereHttpCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VendorLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VendorLog whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VendorLog whereVendorService($value)
 * @mixin \Eloquent
 */
class VendorLog extends Model
{
    use HasFactory;

    public $fillable=[
        'message',
        'vendor_service',
        'http_code'
    ];

    public $appends = ['time'];

    public function getTimeAttribute(): string
    {
        return date('H:i:s d-m-Y', strtotime($this->created_at));
    }
}
