<?php

namespace Webkul\Inventory\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Webkul\Inventory\Models\Delivery;
use Webkul\Security\Models\User;
use Webkul\Security\Traits\HasScopedPermissions;

class DeliveryPolicy
{
    use HandlesAuthorization, HasScopedPermissions;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_delivery');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Delivery $delivery): bool
    {
        return $user->can('view_delivery');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_delivery');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Delivery $delivery): bool
    {
        if (! $user->can('update_delivery')) {
            return false;
        }

        return $this->hasAccess($user, $delivery);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Delivery $delivery): bool
    {
        if (! $user->can('delete_delivery')) {
            return false;
        }
        // dd($this->hasAccess($user, $delivery));

        return $this->hasAccess($user, $delivery);
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_delivery');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, Delivery $delivery): bool
    {
        if (! $user->can('force_delete_delivery')) {
            return false;
        }

        return $this->hasAccess($user, $delivery);
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_delivery');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, Delivery $delivery): bool
    {
        if (! $user->can('restore_delivery')) {
            return false;
        }

        return $this->hasAccess($user, $delivery);
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_delivery');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, Delivery $delivery): bool
    {
        if (! $user->can('replicate_delivery')) {
            return false;
        }

        return $this->hasAccess($user, $delivery);
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_delivery');
    }
}
