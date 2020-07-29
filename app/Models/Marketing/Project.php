<?php

namespace App\Models\Marketing;

use App\Models\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use UuidTrait;

    protected $table = 'marketing_projects';

    /*
     * Relations
     */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function parameters()
    {
        return $this->belongsToMany(Parameter::class, 'marketing_projects_has_parameters', 'project_id', 'parameter_id')
            ->using(ProjectParameter::class);
    }
}
