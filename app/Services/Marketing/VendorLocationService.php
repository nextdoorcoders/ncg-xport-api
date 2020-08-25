<?php

namespace App\Services\Marketing;

use App\Models\Marketing\Project as ProjectModel;
use App\Models\Marketing\VendorLocation as VendorLocationModel;
use Illuminate\Database\Eloquent\Collection;

class VendorLocationService
{
    /**
     * @return Collection
     */
    public function allVendors()
    {
        return VendorLocationModel::query()
            ->get();
    }

    /**
     * @param ProjectModel $project
     * @return Collection
     */
    public function freeProjectVendorsLocation(ProjectModel $project)
    {
        return VendorLocationModel::query()
            ->with([
                'vendor',
            ])
            ->where('city_id', $project->city_id)
            ->whereDoesntHave('groups', function ($query) use ($project) {
                $query->where('project_id', $project->id);
            })
            ->get();
    }

    /**
     * @param ProjectModel $project
     * @return Collection
     */
    public function busyProjectVendorsLocation(ProjectModel $project)
    {
        return VendorLocationModel::query()
            ->with([
                'vendor',
            ])
            ->where('city_id', $project->city_id)
            ->whereHas('groups', function ($query) use ($project) {
                $query->where('project_id', $project->id);
            })
            ->get();
    }
}
