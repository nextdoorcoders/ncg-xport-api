<?php

namespace App\Models\Marketing;

use App\Models\Account\User;
use App\Models\Traits\UuidTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Organization
 *
 * @package App\Models\Marketing
 * @property string $id
 * @property string $user_id
 * @property string $name
 * @property string $zip
 * @property string $email
 * @property array  $phones
 * @property array  $addresses
 * @property array  $working_hours
 * @property array  $social_networks
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Organization extends Model
{
    use UuidTrait;

    protected $table = 'marketing_organizations';

    protected $fillable = [
        'user_id',
        'name',
        'zip',
        'email',
        'phones',
        'addresses',
        'working_hours',
        'social_networks',
    ];

    protected $casts = [
        'phones'          => 'array',
        'addresses'       => 'array',
        'working_hours'   => 'array',
        'social_networks' => 'array',
    ];

    /*
     * Relations
     */

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return HasMany
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class,'project_id');
    }
}
