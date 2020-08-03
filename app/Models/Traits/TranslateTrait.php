<?php

namespace App\Models\Traits;

use App\Models\Account\Language;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait TranslateTrait
{
    use UuidTrait;

    /**
     * @return BelongsTo
     */
    public function translatable(): BelongsTo
    {
        return $this->belongsTo($this->getTranslatableClass(), 'translatable_id');
    }

    /**
     * @return BelongsTo
     */
    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'language_id');
    }

    /**
     * @return false|string
     */
    public function getTranslatableClass(): string
    {
        return get_class($this);
    }
}
