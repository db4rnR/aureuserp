<?php

namespace Webkul\Account\Filament\Resources\FiscalPositionResource\Pages;

use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Webkul\Account\Filament\Resources\FiscalPositionResource;

class ViewFiscalPosition extends ViewRecord
{
    protected static string $resource = FiscalPositionResource::class;

    static function getSubNavigationPosition(): SubNavigationPosition
    {
        return SubNavigationPosition::Top;
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('accounts::filament/resources/fiscal-position/pages/view-fiscal-position.header-actions.delete.notification.title'))
                        ->body(__('accounts::filament/resources/fiscal-position/pages/view-fiscal-position.header-actions.delete.notification.body'))
                ),
        ];
    }
}
