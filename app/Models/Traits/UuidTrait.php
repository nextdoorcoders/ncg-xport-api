<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait UuidTrait
{
    public function initializeUuidTrait(): void
    {
        $this->setIncrementing(false);
        $this->setKeyType('string');
    }

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function bootUuidTrait()
    {
        self::creating(function (Model $model) {
            $model->setAttribute($model->getKeyName(), $model->getAttribute($model->getKeyName()) ?: (string)Str::orderedUuid());
        });
    }
}
