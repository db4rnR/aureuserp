<?php

namespace Webkul\Purchase\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Webkul\Purchase\Models\CustomerPurchaseOrder;
use Webkul\Website\Models\Partner;

class CustomerPurchaseOrderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Partner $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Partner $user, CustomerPurchaseOrder $purchaseOrder): bool
    {
        return $user->id === $purchaseOrder->partner_id;
    }
}
