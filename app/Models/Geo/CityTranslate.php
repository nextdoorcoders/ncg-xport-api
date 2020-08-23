<?php

namespace App\Models\Geo;

use App\Models\Traits\TranslateTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CityTranslate
 *
 * @package App\Models\Geo
 * @property string $language_id
 * @property string $translatable_id
 * @property string $name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class CityTranslate extends Model
{
    use TranslateTrait;

    protected $table = 'geo_cities_translate';

    protected $fillable = [
        'name',
    ];
}
