<?php

declare(strict_types=1);

namespace Webkul\Account\Filament\Resources\InvoiceResource\Actions;

use Filament\Actions\Action;
use Livewire\Component;
use Webkul\Account\Enums\MoveState;
use Webkul\Account\Facades\Account;
use Webkul\Account\Models\Move;

class ResetToDraftAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(__('accounts::filament/resources/invoice/actions/reset-to-draft-action.title'))
            ->color('gray')
            ->icon('heroicon-o-arrow-path')
            ->action(function (Move $record, Component $livewire): void {
                $record = Account::resetToDraft($record);

                $record->save();

                $livewire->refreshFormData(['state', 'parent_state']);
            })
            ->visible(fn (Move $record): bool => $record->state === MoveState::CANCEL
            || $record->state === MoveState::POSTED);
    }

    public static function getDefaultName(): ?string
    {
        return 'customers.invoice.reset-to-draft';
    }
}
