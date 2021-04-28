<?php

namespace App\Models;

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
 * @method static \Illuminate\Database\Eloquent\Builder|VendorState newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VendorState newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VendorState query()
 * @method static \Illuminate\Database\Eloquent\Builder|VendorState whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VendorState whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VendorState whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VendorState whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VendorState whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read string $state_for_show
 */
class VendorState extends Model
{
    use HasFactory;


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
