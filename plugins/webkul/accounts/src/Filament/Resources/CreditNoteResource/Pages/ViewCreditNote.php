<?php

declare(strict_types=1);

namespace Webkul\Account\Filament\Resources\CreditNoteResource\Pages;

use Webkul\Account\Filament\Resources\CreditNoteResource;
use Webkul\Account\Filament\Resources\InvoiceResource\Actions as BaseActions;
use Webkul\Account\Filament\Resources\InvoiceResource\Pages\ViewInvoice as ViewRecord;

final class ViewCreditNote extends ViewRecord
{
    protected static string $resource = CreditNoteResource::class;

    protected function getHeaderActions(): array
    {
        $predefinedActions = parent::getHeaderActions();

        return collect($predefinedActions)->filter(fn ($action): bool => ! in_array($action->getName(), [
            'customers.invoice.set-as-checked',
            'customers.invoice.credit-note',
        ], true))->map(function ($action): BaseActions\PreviewAction|\Filament\Actions\Action|\Filament\Actions\ActionGroup {
            if ($action->getName() === 'customers.invoice.preview') {
                return BaseActions\PreviewAction::make()
                    ->modalHeading(__('accounts::filament/resources/credit-note/pages/view-credit-note.header-actions.preview.modal-heading'))
                    ->setTemplate('accounts::credit-note/actions/preview.index');
            }

            return $action;
        })->toArray();
    }
}
