<?php

namespace App\Models\Account;

use App\Models\Traits\UuidTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Language
 *
 * @package App\Models\Account
 * @property string  $id
 * @property string  $name
 * @property string  $code
 * @property array   $priority
 * @property boolean $is_primary
 * @property Carbon  $created_at
 * @property Carbon  $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Language newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Language newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Language query()
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereIsPrimary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Language wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Language extends Model
{
    use HasFactory, UuidTrait;

    const LANGUAGE_EN = 'en';
    const LANGUAGE_RU = 'ru';
    const LANGUAGE_UK = 'uk';

    const LANGUAGE_BY_DEFAULT = self::LANGUAGE_EN;

    protected $table = 'account_languages';

    protected $fillable = [
        'name',
        'code',
        'priority',
        'is_primary',
    ];

    protected $casts = [
        'priority' => 'array',
    ];
}
