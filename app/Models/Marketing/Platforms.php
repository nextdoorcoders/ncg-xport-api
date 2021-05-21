<?php

namespace App\Models\Marketing;

use App\Models\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Project
 *
 * @package App\Models\Marketing
 * @property string               $id
 * @property string               $project_id
 * @property string               $platform
 * @property-read int|null $maps_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Project newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Project newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Project query()
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereDateEndAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereDateStartAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereIsEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereParameters($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereUserId($value)
 * @mixin \Eloquent
 */

class Platforms extends Model
{
    use HasFactory, UuidTrait;

    protected $table = 'marketing_platforms';

    protected $fillable = [
        'platform'
    ];

    protected $dates = [

    ];

    protected $casts = [
        'parameters' => 'encrypted:object',
    ];


    /*
     * Relations
     */

}
