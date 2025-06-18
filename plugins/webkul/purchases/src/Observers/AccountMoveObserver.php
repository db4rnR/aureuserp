<?php

declare(strict_types=1);

namespace Webkul\Purchase\Observers;

use Webkul\Purchase\Models\AccountMove;

final class AccountMoveObserver
{
    /**
     * Handle the User "updated" event.
     */
    public function updated($move): void
    {
        if ($move->isDirty('state')) {
            $accountMove = AccountMove::find($move->id);

            $oldValue = $move->getOriginal('state');
            $newValue = $move->state;

            dd($accountMove, $oldValue, $newValue);
        }
    }
}
