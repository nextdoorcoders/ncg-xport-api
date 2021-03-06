<?php

namespace App\Models\Trigger;

use App\Casts\Json;
use App\Models\Geo\Location;
use App\Models\Traits\TranslatableTrait;
use App\Models\Traits\UuidTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class VendorType
 *
 * @package App\Models\Trigger
 * @property string                     $id
 * @property string                     $vendor_id
 * @property string                     $type
 * @property object                     $default_parameters
 * @property object                     $settings
 * @property Carbon                     $created_at
 * @property Carbon                     $updated_at
 * @property array                      $name
 * @property array                      $desc
 * @property Vendor                     $vendor
 * @property Collection<VendorLocation> $vendorsLocations
 * @property Collection<Condition>      $conditions
 * @property Collection<Location>       $locations
 * @property-read int|null $conditions_count
 * @property-read int|null $locations_count
 * @property-read Collection|\Illuminate\Database\Eloquent\Model@anonymous /var/www/html/app/Models/Traits/TranslatableTrait.php:138$746[] $translations
 * @property-read int|null $translations_count
 * @property-read int|null $vendors_locations_count
 * @method static \Illuminate\Database\Eloquent\Builder|VendorType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VendorType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VendorType query()
 * @method static \Illuminate\Database\Eloquent\Builder|VendorType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VendorType whereDefaultParameters($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VendorType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VendorType whereSettings($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VendorType whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VendorType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VendorType whereVendorId($value)
 * @mixin \Eloquent
 */
class VendorType extends Model
{
    use HasFactory, TranslatableTrait, UuidTrait;

    protected $table = 'trigger_vendors_types';

    protected $fillable = [
        'vendor_id',
        'type',
        'default_parameters',
        'settings',
    ];

    protected $translatable = [
        'name',
        'desc',
    ];

    protected $casts = [
        'default_parameters' => Json::class,
        'settings'           => Json::class,
    ];

    /*
     * Relations
     */

    /**
     * @return BelongsTo
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    /**
     * @return HasMany
     */
    public function vendorsLocations(): HasMany
    {
        return $this->hasMany(VendorLocation::class, 'vendor_type_id');
    }

    /**
     * @return HasMany
     */
    public function conditions(): HasMany
    {
        return $this->hasMany(Condition::class, 'vendor_type_id');
    }

    /**
     * @return BelongsToMany
     */
    public function locations(): BelongsToMany
    {
        return $this->belongsToMany(Location::class, 'trigger_vendors_locations', 'vendor_type_id', 'location_id')
            ->withPivot([
                'id',
            ])
            ->using(VendorLocation::class);
    }
}
