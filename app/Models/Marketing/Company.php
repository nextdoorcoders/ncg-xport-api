<?php

namespace App\Models\Marketing;

use App\Models\Account\User;
use App\Models\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use UuidTrait;

    protected $table = 'marketing_companies';

    /*
     * Relations
     */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function projects()
    {
        return $this->hasMany(Project::class, 'company_id');
    }
}
