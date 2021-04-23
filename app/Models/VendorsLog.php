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
 * @method static \Illuminate\Database\Eloquent\Builder|VendorsLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VendorsLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VendorsLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|VendorsLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VendorsLog whereHttpCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VendorsLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VendorsLog whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VendorsLog whereVendorService($value)
 * @mixin \Eloquent
 */
class VendorsLog extends Model
{
    use HasFactory;

    public $fillable=[
        'message',
        'vendor_service',
        'http_code'
    ];
}
