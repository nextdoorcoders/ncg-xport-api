<?php

namespace App\Models\Traits;

use App\Models\Account\Language;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait TranslatableTrait
{
    protected array $translationAttributes = [];

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

    public static function bootTranslatableTrait()
    {
        // Добавляем глобальный scope при помощи которого всегда будем вытягивать переводы
        static::addGlobalScope('translations', function (Builder $builder) {
            $builder->with([
                'translations' => function ($query) {
                    $query->with('language');
                },
            ]);
        });

        // Получаем модель с готовым списком полей и переводов
        static::retrieved(function (self $model) {
            collect($model->getTranslatable())
                ->each(function ($columnName) use ($model) {
                    $model->setAttribute($columnName, $model->translations->pluck($columnName, 'language.code'));
                });
        });

        // Препроцессор сохранения модели
        static::saving(function (self $model) {
            $translatable = $model->getTranslatable();
            $attributes = $model->getAttributes();

            $cleanAttributes = array_diff_key($attributes, array_flip($translatable));
            $cleanTranslationAttributes = array_diff_key($attributes, $cleanAttributes);

            foreach ($cleanTranslationAttributes as $translateColumnName => $translateColumnValues) {
                if (in_array($translateColumnName, $model->getTranslatable())) {
                    foreach ($translateColumnValues as $language => $value) {
                        $model->translationAttributes[$language][$translateColumnName] = $value;
                    }
                }
            }

            $model->setRawAttributes($cleanAttributes);
        });

        // Постпроцессор сохранения модели
        static::saved(function (self $model) {
            $languages = Language::query()
                ->get();

            foreach ($languages as $language) {
                $translationAttributes = $model->translationAttributes[$language->code] ?? null;

                if ($translationAttributes) {
                    $model->translations()
                        ->updateOrCreate([
                            'language_id'     => $language->id,
                            'translatable_id' => $model->id,
                        ], $translationAttributes);
                }
            }
        });

        // Удаляем все переводы вместе с родительской записью
        static::deleted(function ($model) {
            $model->traslations()
                ->delete();
        });
    }

    /**
     * @return string
     */
    public function getTranslateClass(): string
    {
        return get_class($this) . 'Translate';
    }
}
