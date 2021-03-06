<?php

namespace App\Models\Account;

use App\Models\Traits\UuidTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;

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
 * @property User   $user
 * @property-read array|\ArrayAccess|mixed $type_text
 * @method static \Illuminate\Database\Eloquent\Builder|Contact newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Contact newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Contact query()
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereValue($value)
 * @mixin \Eloquent
 */
class Contact extends Model
{
    use HasFactory, UuidTrait;

    const TYPE_PHONE = 'phone';
    const TYPE_EMAIL = 'email';
    const TYPE_WEBSITE = 'website';
    const TYPE_ADDRESS = 'address';

    const TYPES = [
        self::TYPE_PHONE,
        self::TYPE_EMAIL,
        self::TYPE_WEBSITE,
        self::TYPE_ADDRESS,
    ];

    protected $table = 'account_contacts';

    protected $fillable = [
        'type',
        'value',
    ];

    protected $appends = [
        'type_text',
    ];

    /*
     * Relations
     */

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /*
     * Static methods
     */

    /**
     * @return array
     */
    public static function getTypes()
    {
        return array_combine(self::TYPES, [
            'Phone',
            'Email',
            'Web site',
            'Address',
        ]);
    }

    /*
     * Accessors
     */

    /**
     * @return array|\ArrayAccess|mixed
     */
    public function getTypeTextAttribute()
    {
        return Arr::get(self::getTypes(), $this->type);
    }
}
