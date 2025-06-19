<?php

declare(strict_types=1);

namespace Webkul\Account\Filament\Resources\PaymentsResource\Actions;

use Filament\Actions\Action;
use Livewire\Component;
use Webkul\Account\Enums\PaymentStatus;
use Webkul\Account\Models\Payment;

class CancelAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(__('accounts::filament/resources/payment/actions/cancel-action.title'))
            ->color('gray')
            ->action(function (Payment $record, Component $livewire): void {
                $record->state = PaymentStatus::CANCELED->value;
                $record->save();

                $livewire->refreshFormData(['state']);
            })
            ->hidden(fn (Payment $record): bool => $record->state === PaymentStatus::CANCELED->value);
    }

    public static function getDefaultName(): ?string
    {
        return 'customers.payment.cancel';
    }
}
