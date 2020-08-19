<?php

namespace App\Models\Traits;

use App\Models\Account\Language;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Trait TranslatableTrait
 *
 * @package App\Models\Traits
 * @property Collection<Model> $translations
 * @property array             $translatable
 */
trait TranslatableTrait
{
    protected array $translationAttributes = [];

    public static function bootTranslatableTrait(): void
    {
        static::addGlobalScope('translations', function (Builder $builder) {
            $builder->with([
                'translations' => function ($query) {
                    $query->with('language');
                },
            ]);
        });

        static::retrieved(function (self $model) {
            self::setTranslationAttributes($model);
        });

        static::saving(function (self $model) {
            self::purgeableTranslationAttributes($model);
        });

        static::saved(function (self $model) {
            self::createTranslationModels($model);

            self::setTranslationAttributes($model);
        });

        static::deleted(function ($model) {
            $model->traslations()
                ->delete();
        });
    }

    protected function initializeTranslatableTrait(): void
    {
        $this->mergeFillable($this->getTranslatable());
    }

    /**
     * @param TranslatableTrait $model
     */
    protected static function purgeableTranslationAttributes(self $model): void
    {
        $translatable = $model->getTranslatable();
        $attributes = $model->getAttributes();

        $cleanAttributes = array_diff_key($attributes, array_flip($translatable));
        $cleanTranslationAttributes = array_diff_key($attributes, $cleanAttributes);

        foreach ($cleanTranslationAttributes as $translateColumnName => $translateColumnValues) {
            foreach ($translateColumnValues as $language => $value) {
                $model->translationAttributes[$language][$translateColumnName] = $value;
            }
        }

        $model->setRawAttributes($cleanAttributes);
    }

    /**
     * @param TranslatableTrait $model
     */
    protected static function createTranslationModels(self $model): void
    {
        $languages = Language::query()
            ->get();

        foreach ($languages as $language) {
            $translationAttributes = $model->translationAttributes[$language->code] ?? null;

            if ($translationAttributes) {
                // TODO: Delete all, create new
                $model->translations()
                    ->updateOrCreate([
                        'language_id' => $language->id,
                    ], $translationAttributes);
            }
        }
    }

    /**
     * @param TranslatableTrait $model
     */
    protected static function setTranslationAttributes(self $model): void
    {
        collect($model->getTranslatable())
            ->each(function ($columnName) use ($model) {
                $columnValues = $model->translations->pluck($columnName, 'language.code');

                $model->setAttribute($columnName, $columnValues);
            });
    }

    /**
     * @return HasMany
     */
    public function translations(): HasMany
    {
        return $this->hasMany($this->getTranslateClass(), 'translatable_id');
    }

    /**
     * @return array
     */
    public function getTranslatable(): array
    {
        return $this->translatable;
    }

    /**
     * @return string
     */
    public function getTranslateClass(): string
    {
        return get_class($this) . 'Translate';
    }
}
