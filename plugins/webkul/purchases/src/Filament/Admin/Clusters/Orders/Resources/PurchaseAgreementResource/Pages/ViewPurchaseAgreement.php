<?php

namespace Webkul\Purchase\Filament\Admin\Clusters\Orders\Resources\PurchaseAgreementResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Webkul\Chatter\Filament\Actions\ChatterAction;
use Webkul\Purchase\Enums;
use Webkul\Purchase\Filament\Admin\Clusters\Orders\Resources\PurchaseAgreementResource;

class ViewPurchaseAgreement extends ViewRecord
{
    protected static string $resource = PurchaseAgreementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ChatterAction::make()
                ->setResource(static::$resource),
            Actions\DeleteAction::make()
                ->hidden(fn () => $this->getRecord()->state == Enums\RequisitionState::CLOSED)
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('inventories::filament/clusters/orders/resources/purchase-agreement/pages/view-purchase-agreement.header-actions.delete.notification.title'))
                        ->body(__('inventories::filament/clusters/orders/resources/purchase-agreement/pages/view-purchase-agreement.header-actions.delete.notification.body')),
                ),
        ];
    }
}
