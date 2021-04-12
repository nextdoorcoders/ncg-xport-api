<?php

namespace App\Models\Vendor;

use App\Models\Traits\UuidTrait;
use App\Models\Trigger\Condition;
use App\Models\Trigger\VendorLocation;
use App\Models\Trigger\VendorType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class KeywordRate
 *
 * @package App\Models\Vendor
 * @property string         $id
 * @property string         $vendor_type_id
 * @property string         $vendor_location_id
 * @property string         $keyword_id
 * @property string         $value
 * @property Carbon         $created_at
 * @property Carbon         $updated_at
 * @property VendorType     $vendorType
 * @property VendorLocation $vendorLocation
 */
class KeywordRate extends Model
{
    use HasFactory, UuidTrait;

    protected $table = 'vendor_keywords_rate';

    protected $fillable = [
        'vendor_type_id',
        'vendor_location_id',
        'keyword_id',
        'value',
    ];

    /*
     * Relations
     */

    /**
     * @return BelongsTo
     */
    public function vendorType()
    {
        return $this->belongsTo(VendorType::class, 'vendor_type_id');
    }

    /**
     * @return BelongsTo
     */
    public function vendorLocation()
    {
        return $this->belongsTo(VendorLocation::class, 'vendor_location_id');
    }

    /**
     * @return BelongsTo
     */
    public function condition(): BelongsTo
    {
        return $this->belongsTo(Condition::class, 'condition_id');
    }
}
