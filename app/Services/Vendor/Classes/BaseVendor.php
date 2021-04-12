<?php

namespace App\Services\Vendor\Classes;

use App\Models\Trigger\Condition as ConditionModel;
use Illuminate\Support\Str;

abstract class BaseVendor
{
    /**
     * @param ConditionModel $condition
     * @return mixed
     */
    public function getCurrentValue(ConditionModel $condition)
    {
        $condition->loadMissing([
            'vendorType',
            'vendorLocation',
        ]);

        $method = 'current' . Str::ucfirst($condition->vendorType->type);

        return $this->{$method}($condition);
    }

    /**
     * @param ConditionModel $condition
     * @return mixed
     */
    public function checkCondition(ConditionModel $condition): bool
    {
        $condition->loadMissing([
            'vendorType',
            'vendorLocation',
        ]);

        $this->checking($condition);

        $currentValue = $this->getCurrentValue($condition);
        $status = $this->check($condition, $currentValue);

        $this->checked($condition);

        // Инверсия состояния если необходимо
        return $condition->is_inverted ? !$status : $status;
    }

    /**
     * Список правил вализации при создании и обновлении модели условия
     *
     * @param string $type
     * @return array
     */
    public function getValidateRulesCreating(string $type): array
    {
        return [];
    }

    /**
     * Список правил вализации при создании и обновлении модели условия
     *
     * @param string $type
     * @return array
     */
    public function getValidateRulesUpdating(string $type): array
    {
        return [];
    }

    /**
     * Метод вызываем перед созданием модели условия
     *
     * @param ConditionModel $condition
     */
    public function creating(ConditionModel &$condition)
    {
        //
    }

    /**
     * Метод вызываем перед обновлением модели условия
     *
     * @param ConditionModel $condition
     */
    public function updating(ConditionModel &$condition)
    {
        //
    }

    /**
     * Метод вызываем перед удалением модели условия
     *
     * @param ConditionModel $condition
     */
    public function deleting(ConditionModel &$condition)
    {
        //
    }


    /**
     * Метод вызываем после создания модели условия
     *
     * @param ConditionModel $condition
     */
    public function created(ConditionModel &$condition)
    {
        //
    }

    /**
     * Метод вызываем после обновления модели условия
     *
     * @param ConditionModel $condition
     */
    public function updated(ConditionModel &$condition)
    {
        //
    }

    /**
     * Метод вызываем после удаления модели условия
     *
     * @param ConditionModel $condition
     */
    public function deleted(ConditionModel &$condition)
    {
        //
    }


    /**
     * Метод вызываемый перед началом проверки состояния
     *
     * @param ConditionModel $condition
     */
    protected function checking(ConditionModel &$condition): void
    {

    }

    /**
     * Метод вызываемый после окончания проверки состояния
     *
     * @param ConditionModel $condition
     */
    protected function checked(ConditionModel &$condition): void
    {

    }
}
