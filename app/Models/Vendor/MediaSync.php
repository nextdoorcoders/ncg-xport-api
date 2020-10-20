<?php

namespace App\Models\Vendor;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MediaSync
 *
 * @package App\Models\Vendor
 * @property string $id
 * @property string $vendor_id
 * @property string $vendor_location_id
 * @property string $source
 * @property string $type
 * @property string $value
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class MediaSync extends Model
{
    use HasFactory;

    const SOURCE_SYNC_NCG = 'sync_ncg';

    protected $table = 'vendor_media_sync';
}
