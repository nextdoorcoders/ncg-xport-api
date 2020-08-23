<?php

namespace App\Models\Geo;

use App\Models\Traits\TranslateTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class StateTranslate
 *
 * @package App\Models\Geo
 * @property string $language_id
 * @property string $translatable_id
 * @property string $name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class StateTranslate extends Model
{
    use TranslateTrait;

    protected $table = 'geo_states_translate';

    protected $fillable = [
        'name',
    ];
}
