<?php

namespace App\Models\Geo;

use App\Models\Traits\TranslateTrait;
use App\Models\Traits\UuidTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CountryTranslate
 *
 * @package App\Models\Geo
 * @property string $id
 * @property string $language_id
 * @property string $translatable_id
 * @property string $name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class CountryTranslate extends Model
{
    use TranslateTrait, UuidTrait;

    protected $table = 'geo_countries_translate';

    protected $fillable = [
        'name',
    ];
}
