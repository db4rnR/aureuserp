<?php

declare(strict_types=1);

namespace Webkul\Account\Filament\Resources\PaymentsResource\Actions;

use Filament\Actions\Action;
use Livewire\Component;
use Webkul\Account\Enums\PaymentStatus;
use Webkul\Account\Models\Payment;

final class ResetToDraftAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(__('accounts::filament/resources/payment/actions/reset-to-draft.title'))
            ->color('gray')
            ->action(function (Payment $record, Component $livewire): void {
                $record->state = PaymentStatus::DRAFT->value;
                $record->save();

                $livewire->refreshFormData(['state']);
            })
            ->hidden(fn (Payment $record): bool => $record->state === PaymentStatus::DRAFT->value);
    }

    public static function getDefaultName(): ?string
    {
        return 'customers.payment.reset-to-draft';
    }
}
