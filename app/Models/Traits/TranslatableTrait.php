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
            /*
             * Для всех переводимых моделей автоматически будут подгружены переводы,
             * во избежание отправки дополнительных запросов в БД в будущем
             */
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
        /*
         * Удаляем переводимые поля из модели, сохраняем их во временное
         * хранилище из которого будем читать их при сохранении переводов
         */

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
            /*
             * Читаем список полей из временного хранилища согласно текущему языку
             * в цикле. Обновляем или создаём перевод
             */
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
                /*
                 * У корневой (переводимой) модели каждое переводимое поле - это массив.
                 * Где ключ - это код языка (en, ru, uk), а значение - это перевод поля
                 */
                $columnValues = $model->translations->pluck($columnName, 'language.code');

                // Полученный массив добавляем в список атрибутов корневой (переводимой) модели
                $model->setAttribute($columnName, $columnValues);
            });
    }

    /**
     * @return HasMany
     */
    public function translations(): HasMany
    {
        /*
         * Создание анонимного класса позволяет не создавать
         * отделюную модель для каждой таблицы с переводами
         */
        $translateModel = new class extends Model {
            use TranslateTrait;
        };

        $related = get_class($translateModel);
        $foreignKey = 'translatable_id';
        $localKey = null;

        /*
         * Модифицированное отношение hasMany.
         * Стандартный метод hasMany не позволяет передавать экземпляр класса
         * с модифицированными свойствами, по этому мы передаем путь к классу,
         * полученый экземпляр модифицируем и дальше работает стандартный код
         * метода hasMany без изменений
         */
        $instance = $this->newRelatedInstance($related);

        // Проводим модификацию экземпляра модели
        $instance->setTable($this->table . '_translate');
        $instance->mergeFillable($this->getTranslatable());

        // Далее стандартный код метода hasMany
        $foreignKey = $foreignKey ?: $this->getForeignKey();

        $localKey = $localKey ?: $this->getKeyName();

        return $this->newHasMany($instance->newQuery(), $this, $instance->getTable() . '.' . $foreignKey, $localKey);
    }

    /**
     * @return array
     */
    public function getTranslatable(): array
    {
        return $this->translatable;
    }
}
