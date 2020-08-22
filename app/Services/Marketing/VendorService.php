<?php

namespace App\Services\Marketing;

use App\Models\Marketing\Project as ProjectModel;
use App\Models\Marketing\Vendor as VendorModel;

class VendorService
{
    public function allVendors()
    {
        return VendorModel::query()
            ->get();
    }

    /**
     * @param ProjectModel $project
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
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
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
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
