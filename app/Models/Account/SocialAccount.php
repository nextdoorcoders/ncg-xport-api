<?php

namespace App\Models\Account;

use App\Models\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Model;

class SocialAccount extends Model
{
    use UuidTrait;

    protected $table = 'account_socials';

    protected $fillable = [
        'uuid',
        'user_id',
        'provider_id',
        'provider_name',
        'email',
        'last_login_at',
    ];

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
}
