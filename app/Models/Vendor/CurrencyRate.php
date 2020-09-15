<?php

namespace App\Models\Vendor;

use App\Models\Geo\Location;
use App\Models\Traits\UuidTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class CurrencyRate
 *
 * @package App\Models\Vendor
 * @property string   $id
 * @property string   $location_id
 * @property string   $from_currency_id
 * @property string   $to_currency_id
 * @property string   $source
 * @property string   $type
 * @property array    $rate
 * @property Carbon   $created_at
 * @property Carbon   $updated_at
 * @property Location $location
 * @property Currency $fromCurrency
 * @property Currency $toCurrency
 */
class CurrencyRate extends Model
{
    use UuidTrait;

    const SOURCE_MINFIN = 'minfin';

    const TYPE_EXCHANGE_RATE = 'exchange';
    const TYPE_INTERBANK_RATE = 'interbank';
    const TYPE_NATIONAL_RATE = 'national';

    const TYPE_OF_RATE_MIN = 'min';
    const TYPE_OF_RATE_AVG = 'avg';
    const TYPE_OF_RATE_MAX = 'max';

    protected $table = 'vendor_currency_rate';

    protected $fillable = [
        'from_currency_id',
        'to_currency_id',
        'location_id',
        'source',
        'type',
        'rate',
    ];

    protected $casts = [
        'rate' => 'array',
    ];

    /*
     * Relations
     */

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

    /**
     * @return BelongsTo
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_id');
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

        $replicate->rate = collect($replicate->rate)->map(function ($item, $key) {
            return round(1 / $item, 4);
        });

        $replicate->push();

        return $replicate;
    }
}