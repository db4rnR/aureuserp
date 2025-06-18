<?php

declare(strict_types=1);

namespace Webkul\Account\Filament\Resources\CreditNoteResource\Pages;

use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Webkul\Account\Facades\Account;
use Webkul\Account\Filament\Resources\CreditNoteResource;
use Webkul\Account\Filament\Resources\InvoiceResource\Actions as BaseActions;
use Webkul\Account\Filament\Resources\InvoiceResource\Pages\EditInvoice as EditRecord;

final class EditCreditNote extends EditRecord
{
    protected static string $resource = CreditNoteResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title(__('accounts::filament/resources/credit-note/pages/edit-credit-note.notification.title'))
            ->body(__('accounts::filament/resources/credit-note/pages/edit-credit-note.notification.body'));
    }

    protected function getHeaderActions(): array
    {
        $predefinedActions = parent::getHeaderActions();

        return collect($predefinedActions)->filter(fn ($action): bool => ! in_array($action->getName(), [
            'customers.invoice.set-as-checked',
            'customers.invoice.credit-note',
        ], true))->map(function ($action): BaseActions\PreviewAction|\Filament\Actions\Action|\Filament\Actions\ActionGroup {
            if ($action->getName() === 'customers.invoice.preview') {
                return BaseActions\PreviewAction::make()
                    ->modalHeading(__('accounts::filament/resources/credit-note/pages/edit-credit-note.header-actions.preview.modal-heading'))
                    ->setTemplate('accounts::credit-note/actions/preview.index');
            }

            return $action;
        })->toArray();
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        Auth::user();

        $record = $this->getRecord();

        $data['partner_id'] ??= $record->partner_id;
        $data['invoice_date'] ??= $record->invoice_date;
        $data['name'] ??= $record->name;
        $data['auto_post'] ??= $record->auto_post;
        $data['invoice_currency_rate'] ??= 1.0;

        return $data;
    }

    protected function afterSave(): void
    {
        Account::computeAccountMove($this->getRecord());
    }
}
