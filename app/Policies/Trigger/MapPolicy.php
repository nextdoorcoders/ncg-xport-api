<?php

namespace App\Policies\Trigger;

use App\Models\Account\User;
use App\Models\Trigger\Map;
use Illuminate\Auth\Access\HandlesAuthorization;

class MapPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\Account\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\Account\User  $user
     * @param  \App\Models\Trigger\Map  $map
     * @return mixed
     */
    public function view(User $user, Map $map)
    {
        if ($user->can('view all maps')) {
            return true;
        }

        return $user->id === $map->user_id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\Account\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\Account\User  $user
     * @param  \App\Models\Trigger\Map  $map
     * @return mixed
     */
    public function update(User $user, Map $map)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\Account\User  $user
     * @param  \App\Models\Trigger\Map  $map
     * @return mixed
     */
    public function delete(User $user, Map $map)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\Account\User  $user
     * @param  \App\Models\Trigger\Map  $map
     * @return mixed
     */
    public function restore(User $user, Map $map)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\Account\User  $user
     * @param  \App\Models\Trigger\Map  $map
     * @return mixed
     */
    public function forceDelete(User $user, Map $map)
    {
        //
    }
}
