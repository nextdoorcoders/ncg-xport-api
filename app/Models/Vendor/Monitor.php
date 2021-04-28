<?php

namespace App\Models\Vendor;

use App\Models\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Vendor\Monitor
 *
 * @property string $id
 * @property string $vendor_type_id
 * @property string|null $vendor_location_id
 * @property string $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Monitor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Monitor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Monitor query()
 * @method static \Illuminate\Database\Eloquent\Builder|Monitor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Monitor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Monitor whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Monitor whereValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Monitor whereVendorLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Monitor whereVendorTypeId($value)
 * @mixin \Eloquent
 */
class Monitor extends Model
{
    use HasFactory, UuidTrait;

    protected $table = 'vendor_monitors';

    protected $fillable = [
        'vendor_type_id',
        'vendor_location_id',
        'value',
    ];
}
