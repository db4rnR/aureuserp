<?php

declare(strict_types=1);

namespace Webkul\Account\Filament\Resources\InvoiceResource\Actions;

use Filament\Actions\Action;
use Livewire\Component;
use Webkul\Account\Enums\MoveState;
use Webkul\Account\Enums\MoveType;
use Webkul\Account\Facades\Account;
use Webkul\Account\Models\Move;

final class CancelAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(__('accounts::filament/resources/invoice/actions/cancel-action.title'))
            ->color('gray')
            ->action(function (Move $record, Component $livewire): void {
                $record = Account::cancel($record);

                $livewire->refreshFormData(['state', 'parent_state']);
            })
            ->hidden(fn (Move $record): bool => $record->state !== MoveState::DRAFT
            || $record->move_type === MoveType::ENTRY);
    }

    public static function getDefaultName(): ?string
    {
        return 'customers.invoice.cancel';
    }
}
