<?php

declare(strict_types=1);

namespace Webkul\Account\Filament\Resources\AccountResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Webkul\Account\Filament\Resources\AccountResource;

final class EditAccount extends EditRecord
{
    protected static string $resource = AccountResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('accounts::filament/resources/account/pages/edit-account.header-actions.delete.notification.title'))
                        ->body(__('accounts::filament/resources/account/pages/edit-account.header-actions.delete.notification.body'))
                ),
        ];
    }

    private function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title(__('accounts::filament/resources/account/pages/edit-account.notification.title'))
            ->body(__('accounts::filament/resources/account/pages/edit-account.notification.body'));
    }
}
