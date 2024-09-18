<?php

namespace App\Policies;

use App\Enums\UserRoles;
use App\Models\RepToPascalThis;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RepToPascalThisPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        if ($user->roles->isEmpty()) {
            return false;
        }

        if (! app()->environment('production') && $user->hasRole(UserRoles::DEVELOPER->value)) {
            return true;
        }

        if ($user->hasPermission('RepToSnakeThis-readAny')) {
            return true;
        }
    }

    public function view(User $user, ?RepToPascalThis $RepToCamelThis = null)
    {
        if ($user->roles->isEmpty()) {
            return false;
        }

        if (! app()->environment('production') && $user->hasRole(UserRoles::DEVELOPER->value)) {
            return true;
        }

        if ($user->hasPermission('RepToSnakeThis-read')) {
            return true;
        }
    }

    public function create(User $user)
    {
        if ($user->roles->isEmpty()) {
            return false;
        }

        if (! app()->environment('production') && $user->hasRole(UserRoles::DEVELOPER->value)) {
            return true;
        }

        if ($user->hasPermission('RepToSnakeThis-create')) {
            return true;
        }
    }

    public function update(User $user, ?RepToPascalThis $RepToCamelThis = null)
    {
        if ($user->roles->isEmpty()) {
            return false;
        }

        if (! app()->environment('production') && $user->hasRole(UserRoles::DEVELOPER->value)) {
            return true;
        }

        if ($user->hasPermission('RepToSnakeThis-update')) {
            return true;
        }
    }

    public function delete(User $user, ?RepToPascalThis $RepToCamelThis = null)
    {
        if ($user->roles->isEmpty()) {
            return false;
        }

        if (! app()->environment('production') && $user->hasRole(UserRoles::DEVELOPER->value)) {
            return true;
        }

        if ($user->hasPermission('RepToSnakeThis-delete')) {
            return true;
        }
    }

    public function restore(User $user, RepToPascalThis $RepToCamelThis)
    {
        return false;
    }

    public function forceDelete(User $user, RepToPascalThis $RepToCamelThis)
    {
        return false;
    }
}
