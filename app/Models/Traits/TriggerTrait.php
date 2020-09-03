<?php

namespace App\Models\Traits;

use Carbon\Carbon;

/**
 * Trait TriggerTrait
 *
 * @package App\Models\Traits
 * @property boolean $is_trigger_enabled
 * @property Carbon  $trigger_refreshed_at
 * @property Carbon  $trigger_changed_at
 */
trait TriggerTrait
{
    public function initializeTriggerTrait(): void
    {
        $this->mergeFillable([
            'is_trigger_enabled',
            'trigger_refreshed_at',
            'trigger_changed_at',
        ]);

        $this->dates = array_merge($this->dates, [
            'trigger_refreshed_at',
            'trigger_changed_at',
        ]);
    }
}
