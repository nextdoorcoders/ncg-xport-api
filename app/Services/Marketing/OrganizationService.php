<?php

namespace App\Services\Marketing;

use App\Models\Account\User as UserModel;
use App\Models\Marketing\Organization as OrganizationModel;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class OrganizationService
{
    /**
     * @param UserModel $user
     * @return Collection
     */
    public function allOrganizations(UserModel $user)
    {
        return $user->organizations()
            ->get();
    }

    /**
     * @param UserModel $user
     * @param array     $data
     * @return OrganizationModel
     */
    public function createOrganization(UserModel $user, array $data)
    {
        /** @var OrganizationModel $organization */
        $organization = $user->organizations()
            ->create($data);

        return $this->readOrganization($organization, $user);
    }

    /**
     * @param OrganizationModel $organization
     * @param UserModel         $user
     * @return OrganizationModel|null
     */
    public function readOrganization(OrganizationModel $organization, UserModel $user)
    {
        return $organization->fresh([
            'user',
            'location',
            'projects',
        ]);
    }

    /**
     * @param OrganizationModel $organization
     * @param UserModel         $user
     * @param array             $data
     * @return OrganizationModel|null
     */
    public function updateOrganization(OrganizationModel $organization, UserModel $user, array $data)
    {
        $organization->fill($data);
        $organization->save();

        return $this->readOrganization($organization, $user);
    }

    /**
     * @param OrganizationModel $organization
     * @param UserModel         $user
     * @throws Exception
     */
    public function deleteOrganization(OrganizationModel $organization, UserModel $user)
    {
        try {
            $organization->delete();
        } catch (Exception $exception) {
            throw $exception;
        }
    }
}
