<?php

namespace App\Models\Geo;

use App\Models\Account\User;
use App\Models\Marketing\Organization;
use App\Models\Traits\NestedTreeTrait;
use App\Models\Traits\TranslatableTrait;
use App\Models\Traits\UuidTrait;
use App\Models\Trigger\VendorLocation;
use App\Models\Trigger\VendorType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Location
 *
 * @package App\Models\Geo
 * @property string                   $id
 * @property string                   $type
 * @property string                   $parent_id
 * @property integer                  $nest_left
 * @property integer                  $nest_right
 * @property integer                  $nest_depth
 * @property array                    $parameters
 * @property Carbon                   $created_at
 * @property Carbon                   $updated_at
 * @property array                    $name
 * @property Collection<Organization> $organization
 * @property Collection<User>         $users
 * @property Collection<VendorType>   $vendorsTypes
 */
class Location extends Model
{
    use HasFactory, NestedTreeTrait, TranslatableTrait, UuidTrait;

    const TYPE_COUNTRY = 'country';
    const TYPE_STATE = 'state';
    const TYPE_CITY = 'city';

    const TYPES = [
        self::TYPE_COUNTRY,
        self::TYPE_STATE,
        self::TYPE_CITY,
    ];

    protected $table = 'geo_locations';

    protected $fillable = [
        'type',
        'parent_id',
        'nest_left',
        'nest_right',
        'nest_depth',
        'parameters',
    ];

    protected $casts = [
        'parameters' => 'array',
    ];

    protected $translatable = [
        'name',
    ];

    /*
     * Relations
     */

    /**
     * @return HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'location_id');
    }

    /**
     * @return BelongsToMany
     */
    public function vendorsTypes(): BelongsToMany
    {
        return $this->belongsToMany(VendorType::class, 'trigger_vendors_locations', 'location_id', 'vendor_type_id')
            ->withPivot([
                'id',
            ])
            ->using(VendorLocation::class);
    }

    /**
     * @return HasMany
     */
    public function vendorsLocations(): HasMany
    {
        return $this->hasMany(VendorLocation::class, 'location_id');
    }
}
