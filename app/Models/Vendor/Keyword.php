<?php

namespace App\Models\Vendor;

use App\Models\Traits\UuidTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Keyword
 *
 * @package App\Models\Vendor
 * @property string                  $uuid
 * @property string                  $code
 * @property string                  $keyword
 * @property Carbon                  $created_at
 * @property Carbon                  $updated_at
 * @property Collection<KeywordRate> $rates
 */
class Keyword extends Model
{
    use HasFactory, UuidTrait;

    protected $table = 'vendor_keywords';

    protected $fillable = [
        'code',
        'keyword',
    ];

    /*
     * Relations
     */

    /**
     * @return HasMany
     */
    public function rates(): HasMany
    {
        return $this->hasMany(KeywordRate::class, 'keyword_id');
    }
}
