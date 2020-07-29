<?php

namespace App\Models\Vendor;

use App\Models\Marketing\Parameter;
use App\Models\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Model;

class Trigger extends Model
{
    use UuidTrait;

    const TYPE_WEATHER = 'weather';

    protected $table = 'vendor_triggers';

    /*
     * Relations
     */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function parameters()
    {
        return $this->hasMany(Parameter::class, 'trigger_id');
    }
}
