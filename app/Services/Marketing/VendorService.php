<?php

namespace App\Services\Marketing;

use App\Models\Marketing\Project as ProjectModel;
use App\Models\Marketing\Vendor as VendorModel;
use Illuminate\Database\Eloquent\Collection;

class VendorService
{
    /**
     * @return Collection
     */
    public function allVendors()
    {
        return VendorModel::query()
            ->get();
    }

    /**
     * @param ProjectModel $project
     * @return Collection
     */
    public function freeProjectVendors(ProjectModel $project)
    {
        return VendorModel::query()
            ->whereDoesntHave('groups', function ($query) use ($project) {
                $query->where('project_id', $project->id);
            })
            ->get();
    }

    /**
     * @param ProjectModel $project
     * @return Collection
     */
    public function busyProjectVendors(ProjectModel $project)
    {
        return VendorModel::query()
            ->whereHas('groups', function ($query) use ($project) {
                $query->where('project_id', $project->id);
            })
            ->get();
    }
}
