<?php

declare(strict_types=1);

namespace Webkul\Account\Filament\Resources\FiscalPositionResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;
use Webkul\Account\Filament\Resources\FiscalPositionResource;

class EditFiscalPosition extends EditRecord
{
    protected static string $resource = FiscalPositionResource::class;

    public static function getSubNavigationPosition(): SubNavigationPosition
    {
        return SubNavigationPosition::Top;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()->success()
            ->title(__('accounts::filament/resources/fiscal-position/pages/edit-fiscal-position.notification.title'))
            ->body(__('accounts::filament/resources/fiscal-position/pages/edit-fiscal-position.notification.body'));
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make()->successNotification(
                    Notification::make()->success()
                        ->title(__('accounts::filament/resources/fiscal-position/pages/edit-fiscal-position.header-actions.delete.notification.title'))
                        ->body(__('accounts::filament/resources/fiscal-position/pages/edit-fiscal-position.header-actions.delete.notification.body'))
                ),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $user = Auth::user();

        $data['company_id'] = $user?->default_company_id;

        return $data;
    }
}
