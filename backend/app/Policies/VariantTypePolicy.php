<?php

namespace App\Policies;

use App\Models\User;
use App\Models\VariantType;
use Illuminate\Auth\Access\Response;

class VariantTypePolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, VariantType $variantType): bool
    {
        return $user->hasRole('Admin');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, VariantType $variantType): bool
    {
        return $user->hasRole('Admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, VariantType $variantType): bool
    {
        return $user->hasRole('Admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, VariantType $variantType): bool
    {
        return false;
    }
}
