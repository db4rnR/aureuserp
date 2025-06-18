<?php

declare(strict_types=1);

namespace Webkul\Account\Filament\Resources\AccountResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Webkul\Account\Filament\Resources\AccountResource;

final class ViewAccount extends ViewRecord
{
    protected static string $resource = AccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('accounts::filament/resources/account/pages/view-account.header-actions.delete.notification.title'))
                        ->body(__('accounts::filament/resources/account/pages/view-account.header-actions.delete.notification.body'))
                ),
        ];
    }
}
