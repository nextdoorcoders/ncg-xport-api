<?php

namespace App\Models\Vendor;

use App\Models\Traits\UuidTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
 * @property-read int|null $from_currency_count
 * @property-read int|null $to_currency_count
 * @method static \Illuminate\Database\Eloquent\Builder|Currency newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Currency newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Currency query()
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereSymbol($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereSymbolBefore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Currency extends Model
{
    use HasFactory, UuidTrait;

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
