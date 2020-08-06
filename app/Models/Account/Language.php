<?php

namespace App\Models\Account;

use App\Models\Geo\CityTranslate;
use App\Models\Geo\CountryTranslate;
use App\Models\Geo\StateTranslate;
use App\Models\Marketing\VendorTranslate;
use App\Models\Traits\UuidTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Language
 *
 * @package App\Models\Account
 * @property string                       $id
 * @property string                       $name
 * @property string                       $code
 * @property boolean                      $is_primary
 * @property Carbon                       $created_at
 * @property Carbon                       $updated_at
 * @property Collection<CountryTranslate> $countryTranslates
 * @property Collection<StateTranslate>   $stateTranslates
 * @property Collection<CityTranslate>    $cityTranslates
 * @property Collection<VendorTranslate>  $vendorTranslates
 */
class Language extends Model
{
    use UuidTrait;

    const LANGUAGE_EN = 'en';
    const LANGUAGE_ES = 'es';
    const LANGUAGE_RU = 'ru';
    const LANGUAGE_PT = 'pt';
    const LANGUAGE_FR = 'fr';
    const LANGUAGE_DE = 'de';
    const LANGUAGE_IT = 'it';
    const LANGUAGE_UK = 'uk';

    const LANGUAGE_BY_DEFAULT = self::LANGUAGE_EN;

    protected $table = 'account_languages';

    protected $fillable = [
        'name',
        'code',
        'is_primary',
    ];

    /*
     * Relations
     */

    /**
     * @return HasMany
     */
    public function countryTranslates(): HasMany
    {
        return $this->hasMany(CountryTranslate::class, 'language_id');
    }

    /**
     * @return HasMany
     */
    public function stateTranslates(): HasMany
    {
        return $this->hasMany(StateTranslate::class, 'language_id');
    }

    /**
     * @return HasMany
     */
    public function cityTranslates(): HasMany
    {
        return $this->hasMany(CityTranslate::class, 'language_id');
    }

    /**
     * @return HasMany
     */
    public function vendorTranslates(): HasMany
    {
        return $this->hasMany(VendorTranslate::class, 'language_id');
    }
}
