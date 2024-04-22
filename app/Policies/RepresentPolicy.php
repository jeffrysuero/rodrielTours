<?php

namespace App\Policies;

use App\Models\Represent;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RepresentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if($user->hasPermissionTo('ver represent')){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Represent $represent): bool
    {
        if($user->hasPermissionTo('ver represent')){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if($user->hasPermissionTo('create represent')){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Represent $represent): bool
    {
        if($user->hasPermissionTo('edit represent')){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Represent $represent): bool
    {
        if($user->hasPermissionTo('delete represent')){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Represent $represent): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Represent $represent): bool
    {
        return false;
    }
}
