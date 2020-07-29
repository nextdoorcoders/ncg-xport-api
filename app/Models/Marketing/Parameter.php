<?php

namespace App\Models\Marketing;

use App\Models\Geo\City;
use App\Models\Traits\UuidTrait;
use App\Models\Vendor\Trigger;
use Illuminate\Database\Eloquent\Model;

class Parameter extends Model
{
    use UuidTrait;

    protected $table = 'marketing_parameters';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function trigger()
    {
        return $this->belongsTo(Trigger::class, 'trigger_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'marketing_projects_has_parameters', 'parameter_id', 'project_id')
            ->using(ProjectParameter::class);
    }
}
