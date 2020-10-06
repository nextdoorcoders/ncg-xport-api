<?php

namespace App\Models\Vendor;

use App\Models\Traits\UuidTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Currency
 *
 * @package App\Models\Vendor
 * @property string                   $id
 * @property string                   $code
 * @property string                   $symbol
 * @property string                   $symbol_before
 * @property Carbon                   $created_at
 * @property Carbon                   $updated_at
 * @property Collection<CurrencyRate> $fromCurrency
 * @property Collection<CurrencyRate> $toCurrency
 */
class Currency extends Model
{
    use UuidTrait;

    protected $table = 'vendor_currencies';

    protected $fillable = [
        'code',
        'symbol',
        'symbol_before',
    ];

    /*
     * Relations
     */

    /**
     * @return HasMany
     */
    public function fromCurrency(): HasMany
    {
        return $this->hasMany(CurrencyRate::class, 'from_currency_id');
    }

    /**
     * @return HasMany
     */
    public function toCurrency(): HasMany
    {
        return $this->hasMany(CurrencyRate::class, 'to_currency_id');
    }
}
