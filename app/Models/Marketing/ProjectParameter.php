<?php

namespace App\Models\Marketing;

use App\Models\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Model;

class ProjectParameter extends Model
{
    use UuidTrait;

    protected $table = 'marketing_projects_has_parameters';

    /*
     * Relations
     */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parameter()
    {
        return $this->belongsTo(Parameter::class, 'parameter_id');
    }
}
