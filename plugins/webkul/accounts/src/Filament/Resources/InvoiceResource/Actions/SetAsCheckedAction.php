<?php

declare(strict_types=1);

namespace Webkul\Account\Filament\Resources\InvoiceResource\Actions;

use Filament\Actions\Action;
use Webkul\Account\Enums\MoveState;
use Webkul\Account\Facades\Account;
use Webkul\Account\Models\Move;

final class SetAsCheckedAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(__('Set as checked'))
            ->label(__('accounts::filament/resources/invoice/actions/set-as-checked-action.title'))
            ->color('gray')
            ->action(function (Move $record, $livewire): void {
                $record = Account::setAsChecked($record);

                $livewire->refreshFormData(['checked']);
            })
            ->hidden(fn (Move $record): bool => $record->checked
            || $record->state === MoveState::DRAFT);
    }

    public static function getDefaultName(): ?string
    {
        return 'customers.invoice.set-as-checked';
    }
}
