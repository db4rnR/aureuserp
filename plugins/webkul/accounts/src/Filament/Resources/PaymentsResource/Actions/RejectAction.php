<?php

declare(strict_types=1);

namespace Webkul\Account\Filament\Resources\PaymentsResource\Actions;

use Filament\Actions\Action;
use Livewire\Component;
use Webkul\Account\Enums\PaymentStatus;
use Webkul\Account\Models\Payment;

final class RejectAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(__('accounts::filament/resources/payment/actions/reject-action.title'))
            ->color('danger')
            ->action(function (Payment $record, Component $livewire): void {
                $record->state = PaymentStatus::REJECTED->value;
                $record->save();

                $livewire->refreshFormData(['state']);
            })
            ->hidden(fn (Payment $record): bool => $record->state !== PaymentStatus::IN_PROCESS->value);
    }

    public static function getDefaultName(): ?string
    {
        return 'customers.payment.reject';
    }
}
