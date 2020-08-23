<?php

namespace App\Models\Traits;

use App\Models\Account\Language;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Trait TranslateTrait
 *
 * @package App\Models\Traits
 * @property Model    $translatable
 * @property Language $language
 */
trait TranslateTrait
{
    protected function initializeTranslateTrait()
    {
        $this->setIncrementing(false);
        $this->setKeyType(null);

        $this->mergeFillable([
            'language_id',
        ]);
    }

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
