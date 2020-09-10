<?php

namespace App\Models\Traits;

use App\Models\Account\Language;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Trait TranslateTrait
 *
 * @package App\Models\Traits
 * @property string   $language_id
 * @property string   $translatable_id
 * @property Carbon   $created_at
 * @property Carbon   $updated_at
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
