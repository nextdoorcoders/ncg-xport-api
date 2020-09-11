<?php

namespace App\Models\Traits;

use App\Models\Account\Language;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
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
    protected array $compositePrimaryKey = [
        'language_id',
        'translatable_id',
    ];

    protected function initializeTranslateTrait(): void
    {
        $this->setIncrementing(false);

        $this->mergeFillable($this->compositePrimaryKey);
    }

    /**
     * Composite primary key
     *
     * @param Builder $query
     * @return Builder
     * @throws Exception
     */
    protected function setKeysForSaveQuery(Builder $query): Builder
    {
        foreach ($this->compositePrimaryKey as $key) {
            if (isset($this->$key)) {
                $query->where($key, '=', $this->$key);
            } else {
                throw new Exception(__METHOD__ . 'Missing part of the primary key: ' . $key);
            }
        }

        return $query;
    }

    /**
     * @return BelongsTo
     */
    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'language_id');
    }
}
