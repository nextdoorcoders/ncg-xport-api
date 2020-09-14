<?php

namespace App\Models\Vendor;

use App\Models\Geo\Location;
use App\Models\Traits\UuidTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Weather
 *
 * @package App\Models\Vendor
 * @property string  $id
 * @property string  $location_id
 * @property Carbon  $datetime_at
 * @property integer $temperature
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
        'temperature',
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
    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }
}
