<?php

namespace App\Models\Vendor;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\VendorsState
 *
 * @property int $id
 * @property string $name
 * @property bool $state
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|States newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|States newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|States query()
 * @method static \Illuminate\Database\Eloquent\Builder|States whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|States whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|States whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|States whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|States whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read string $state_for_show
 */
class States extends Model
{
    use HasFactory;

    protected $table = 'vendor_states';

    public $fillable = ['state'];

    public $appends = ['state_for_show', 'time'];

    public function getStateForShowAttribute(): string
    {
        return $this->state ? 'Active' : 'Has errors';
    }

    public function getTimeAttribute(): string
    {
        return date('H:i:s / d-m-Y', strtotime($this->updated_at) );
    }

    public static function setError($vendorClass)
    {
        /** @var \App\Models\VendorState $item */
        $item = self::whereName( $vendorClass)->first();
        if ($item) {
            $item->update(['state' => false]);
        }
    }

    public static function setActive($vendorClass)
    {
        /** @var \App\Models\VendorState $item */
        $item = self::whereName( $vendorClass)->first();
        if ($item) {
            $item->update(['state' => true]);
        }
    }
}
