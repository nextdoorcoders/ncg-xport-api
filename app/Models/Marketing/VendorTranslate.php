<?php

namespace App\Models\Marketing;

use App\Models\Traits\TranslateTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class VendorTranslate
 *
 * @package App\Models\Marketing
 * @property string $id
 * @property string $language_id
 * @property string $translatable_id
 * @property string $name
 * @property string $desc
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class VendorTranslate extends Model
{
    use TranslateTrait;

    protected $table = 'marketing_vendors_translate';
}
