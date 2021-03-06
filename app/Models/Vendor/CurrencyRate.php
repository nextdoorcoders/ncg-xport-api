<?php

namespace App\Models\Vendor;

use App\Casts\Json;
use App\Models\Traits\UuidTrait;
use App\Models\Trigger\VendorLocation;
use App\Models\Trigger\VendorType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class CurrencyRate
 *
 * @package App\Models\Vendor
 * @property string         $id
 * @property string         $vendor_type_id
 * @property string         $vendor_location_id
 * @property string         $from_currency_id
 * @property string         $to_currency_id
 * @property object         $value
 * @property Carbon         $created_at
 * @property Carbon         $updated_at
 * @property VendorType     $vendorType
 * @property VendorLocation $vendorLocation
 * @property Currency       $fromCurrency
 * @property Currency       $toCurrency
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyRate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyRate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyRate query()
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyRate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyRate whereFromCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyRate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyRate whereToCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyRate whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyRate whereValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyRate whereVendorLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyRate whereVendorTypeId($value)
 * @mixin \Eloquent
 */
class CurrencyRate extends Model
{
    use HasFactory, UuidTrait;

    protected $table = 'vendor_currencies_rate';

    protected $fillable = [
        'vendor_type_id',
        'vendor_location_id',
        'from_currency_id',
        'to_currency_id',
        'value',
    ];

    protected $casts = [
        'value' => Json::class,
    ];

    /*
     * Relations
     */

    /**
     * @return BelongsTo
     */
    public function vendorType(): BelongsTo
    {
        return $this->belongsTo(VendorType::class, 'vendor_type_id');
    }

    /**
     * @return BelongsTo
     */
    public function vendorLocation(): BelongsTo
    {
        return $this->belongsTo(VendorLocation::class, 'vendor_location_id');
    }

    /**
     * @return BelongsTo
     */
    public function fromCurrency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'from_currency_id');
    }

    /**
     * @return BelongsTo
     */
    public function toCurrency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'to_currency_id');
    }

    /*
     * Methods
     */

    /**
     * @return CurrencyRate
     */
    public function createBackRate()
    {
        $replicate = $this->replicate();

        $replicate->from_currency_id = $this->to_currency_id;
        $replicate->to_currency_id = $this->from_currency_id;

        $replicate->value = collect($replicate->value)
            ->map(function ($item, $key) {
                return round(1 / $item, 4);
            });

        $replicate->push();

        return $replicate;
    }
}
