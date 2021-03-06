<?php

namespace App\Models\Geo;

use App\Casts\Json;
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
 * @property object                   $parameters
 * @property Carbon                   $created_at
 * @property Carbon                   $updated_at
 * @property array                    $name
 * @property Collection<Organization> $organization
 * @property Collection<User>         $users
 * @property Collection<VendorType>   $vendorsTypes
 * @property-read Collection|Location[] $children
 * @property-read int|null $children_count
 * @property-read Location|null $parent
 * @property-read Collection|\Illuminate\Database\Eloquent\Model@anonymous /var/www/html/app/Models/Traits/TranslatableTrait.php:138$746[] $translations
 * @property-read int|null $translations_count
 * @property-read int|null $users_count
 * @property-read Collection|VendorLocation[] $vendorsLocations
 * @property-read int|null $vendors_locations_count
 * @property-read int|null $vendors_types_count
 * @method static \Illuminate\Database\Eloquent\Builder|Location allChildren($includeSelf = false)
 * @method static \Illuminate\Database\Eloquent\Builder|Location getAllRoot()
 * @method static \Illuminate\Database\Eloquent\Builder|Location getNested()
 * @method static \Illuminate\Database\Eloquent\Builder|Location leaves()
 * @method static \Illuminate\Database\Eloquent\Builder|Location listsNested($column, $key = null, $indent = '&nbsp;&nbsp;&nbsp;')
 * @method static \Illuminate\Database\Eloquent\Builder|Location newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Location newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Location parents($includeSelf = false)
 * @method static \Illuminate\Database\Eloquent\Builder|Location query()
 * @method static \Illuminate\Database\Eloquent\Builder|Location siblings($includeSelf = false)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereNestDepth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereNestLeft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereNestRight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereParameters($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location withoutNode($node)
 * @method static \Illuminate\Database\Eloquent\Builder|Location withoutRoot()
 * @method static \Illuminate\Database\Eloquent\Builder|Location withoutSelf()
 * @mixin \Eloquent
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
        'parameters' => Json::class,
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
