<?php

namespace App\Models\Geo;

use App\Models\Traits\TranslateTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * Class LocationTranslate
 *
 * @package App\Models\Geo
 * @property string $name
 */
class LocationTranslate extends Model
{
    use TranslateTrait;

    protected $table = 'geo_locations_translate';

    protected $fillable = [
        'name',
    ];
}
