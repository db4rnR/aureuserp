<?php

declare(strict_types=1);

namespace Webkul\Security\Filament\Resources\UserResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Webkul\Security\Filament\Resources\UserResource;

final class ViewUsers extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('security::filament/resources/user/pages/view-user.header-actions.delete.notification.title'))
                        ->body(__('security::filament/resources/user/pages/view-user.header-actions.delete.notification.body')),
                ),
        ];
    }
}
