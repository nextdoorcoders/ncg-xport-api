<?php namespace App\Services\Trigger;

use App\Models\Account\User as UserModel;
use App\Models\Trigger\History as HistoryModel;
use App\Models\Trigger\Map as MapModel;
use Illuminate\Database\Eloquent\Collection;

class HistoryService
{
    /**
     * @param MapModel  $map
     * @param UserModel $user
     * @return Collection
     */
    public function allHistories(MapModel $map, UserModel $user)
    {
        return $map->histories()
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * @param MapModel $map
     * @return HistoryModel
     */
    public function createHistory(MapModel $map)
    {
        /** @var HistoryModel $history */
        $history = $map->histories()
            ->create([
                'is_enabled' => $map->is_enabled,
            ]);

        return $this->readHistory($history);
    }

    /**
     * @param HistoryModel $history
     * @return HistoryModel
     */
    public function readHistory(HistoryModel $history)
    {
        return $history->refresh();
    }
}
