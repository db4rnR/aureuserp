<?php

declare(strict_types=1);

namespace Webkul\Sale\Filament\Clusters\Orders\Resources\QuotationResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Resources\Pages\EditRecord;
use Webkul\Chatter\Filament\Actions as ChatterActions;
use Webkul\Sale\Enums\OrderState;
use Webkul\Sale\Facades\SaleOrder;
use Webkul\Sale\Filament\Clusters\Orders\Resources\QuotationResource;
use Webkul\Sale\Filament\Clusters\Orders\Resources\QuotationResource\Actions as BaseActions;

final class EditQuotation extends EditRecord
{
    protected static string $resource = QuotationResource::class;

    public static function getSubNavigationPosition(): SubNavigationPosition
    {
        return SubNavigationPosition::Top;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', ['record' => $this->getRecord()]);
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title(__('sales::filament/clusters/orders/resources/quotation/pages/edit-quotation.notification.title'))
            ->body(__('sales::filament/clusters/orders/resources/quotation/pages/edit-quotation.notification.body'));
    }

    protected function getHeaderActions(): array
    {
        return [
            ChatterActions\ChatterAction::make()
                ->setResource($this->getResource()),
            BaseActions\BackToQuotationAction::make(),
            BaseActions\CancelQuotationAction::make(),
            BaseActions\ConfirmAction::make(),
            BaseActions\CreateInvoiceAction::make(),
            BaseActions\PreviewAction::make(),
            BaseActions\SendByEmailAction::make(),
            BaseActions\LockAndUnlockAction::make(),
            DeleteAction::make()
                ->hidden(fn (): bool => $this->getRecord()->state === OrderState::SALE)
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('sales::filament/clusters/orders/resources/quotation/pages/edit-quotation.header-actions.notification.delete.title'))
                        ->body(__('sales::filament/clusters/orders/resources/quotation/pages/edit-quotation.header-actions.notification.delete.body')),
                ),
        ];
    }

    private function afterSave(): void
    {
        SaleOrder::computeSaleOrder($this->getRecord());
    }
}
