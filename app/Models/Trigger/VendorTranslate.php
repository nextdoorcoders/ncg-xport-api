<?php

namespace App\Models\Trigger;

use App\Models\Traits\TranslateTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * Class VendorTranslate
 *
 * @package App\Models\Trigger
 * @property string $name
 * @property string $desc
 */
class VendorTranslate extends Model
{
    use TranslateTrait;

    protected $table = 'trigger_vendors_translate';

    protected $fillable = [
        'name',
        'desc',
    ];
}
