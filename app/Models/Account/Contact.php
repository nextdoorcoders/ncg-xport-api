<?php

namespace App\Models\Account;

use App\Models\Traits\UuidTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Contact
 *
 * @package App\Models\Account
 * @property string $id
 * @property string $user_id
 * @property string $type
 * @property string $value
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Contact extends Model
{
    use UuidTrait;

    const TYPE_PHONE = 'phone';
    const TYPE_EMAIL = 'email';
    const TYPE_WEBSITE = 'website';
    const TYPE_ADDRESS = 'address';

    protected $table = 'account_contacts';

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
