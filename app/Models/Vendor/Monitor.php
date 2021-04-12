<?php

namespace App\Models\Vendor;

use App\Models\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Monitor extends Model
{
    use HasFactory, UuidTrait;

    protected $table = 'vendor_monitors';

    protected $fillable = [
        'vendor_type_id',
        'vendor_location_id',
        'value',
    ];
}
