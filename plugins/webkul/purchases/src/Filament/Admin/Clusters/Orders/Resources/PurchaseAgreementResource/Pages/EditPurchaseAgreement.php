<?php

namespace Webkul\Purchase\Filament\Admin\Clusters\Orders\Resources\PurchaseAgreementResource\Pages;

use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Webkul\Chatter\Filament\Actions\ChatterAction;
use Webkul\Purchase\Enums;
use Webkul\Purchase\Filament\Admin\Clusters\Orders\Resources\PurchaseAgreementResource;
use Webkul\Purchase\Models\Requisition;

class EditPurchaseAgreement extends EditRecord
{
    protected static string $resource = PurchaseAgreementResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', ['record' => $this->getRecord()]);
    }

    protected function getSavedNotification(): Notification
    {
        return Notification::make()
            ->success()
            ->title(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement/pages/edit-purchase-agreement.notification.title'))
            ->body(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement/pages/edit-purchase-agreement.notification.body'));
    }

    protected function getHeaderActions(): array
    {
        return [
            ChatterAction::make()
                ->setResource(static::$resource),
            Actions\Action::make('confirm')
                ->label(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement/pages/edit-purchase-agreement.header-actions.confirm.label'))
                ->color('primary')
                ->action(function () {
                    $this->getRecord()->update([
                        'state' => Enums\RequisitionState::CONFIRMED,
                    ]);

                    $this->fillForm();
                })
                ->visible(fn () => $this->getRecord()->state == Enums\RequisitionState::DRAFT),
            Actions\Action::make('close')
                ->label(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement/pages/edit-purchase-agreement.header-actions.close.label'))
                ->color('primary')
                ->action(function () {
                    $this->getRecord()->update([
                        'state' => Enums\RequisitionState::CLOSED,
                    ]);

                    $this->fillForm();
                })
                ->visible(fn () => $this->getRecord()->state == Enums\RequisitionState::CONFIRMED),
            Actions\Action::make('cancelRecord')
                ->label(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement/pages/edit-purchase-agreement.header-actions.cancel.label'))
                ->color('gray')
                ->action(function () {
                    $this->getRecord()->update([
                        'state' => Enums\RequisitionState::CANCELED,
                    ]);

                    $this->fillForm();
                })
                ->visible(fn () => ! in_array($this->getRecord()->state, [
                    Enums\RequisitionState::CLOSED,
                    Enums\RequisitionState::CANCELED,
                ])),
            Actions\Action::make('print')
                ->label(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement/pages/edit-purchase-agreement.header-actions.print.label'))
                ->icon('heroicon-o-printer')
                ->color('gray')
                ->action(function (Requisition $record) {
                    $pdf = PDF::loadView('purchases::filament.admin.clusters.orders.purchase-agreements.print', [
                        'records' => collect([$record]),
                    ]);

                    $pdf->setPaper('a4', 'portrait');

                    return response()->streamDownload(function () use ($pdf) {
                        echo $pdf->output();
                    }, 'Purchase Agreement-'.str_replace('/', '_', $record->name).'.pdf');
                }),
            Actions\DeleteAction::make()
                ->hidden(fn () => $this->getRecord()->state == Enums\RequisitionState::CLOSED)
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement/pages/edit-purchase-agreement.header-actions.delete.notification.title'))
                        ->body(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement/pages/edit-purchase-agreement.header-actions.delete.notification.body')),
                ),
        ];
    }
}
