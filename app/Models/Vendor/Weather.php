<?php

namespace App\Models\Vendor;

use App\Models\Geo\City;
use App\Models\Traits\UuidTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Weather
 *
 * @package App\Models\Vendor
 * @property string  $id
 * @property string  $city_id
 * @property Carbon  $datetime_at
 * @property integer $temp
 * @property integer $wind
 * @property integer $pressure
 * @property integer $humidity
 * @property integer $clouds
 * @property integer $rain
 * @property integer $snow
 * @property Carbon  $created_at
 * @property Carbon  $updated_at
 */
class Weather extends Model
{
    use UuidTrait;

    protected $table = 'vendor_weather';

    protected $fillable = [
        'datetime_at',
        'temp',
        'wind',
        'pressure',
        'humidity',
        'clouds',
        'rain',
        'snow',
    ];

    protected $dates = [
        'datetime_at',
    ];

    /*
     * Relations
     */

    /**
     * @return BelongsTo
     */
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }
}
