<?php

declare(strict_types=1);

namespace Webkul\Account\Filament\Resources\TaxGroupResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\QueryException;
use Webkul\Account\Filament\Resources\TaxGroupResource;
use Webkul\Account\Models\TaxGroup;

final class EditTaxGroup extends EditRecord
{
    protected static string $resource = TaxGroupResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title(__('accounts::filament/resources/tax-group/pages/edit-tax-group.notification.title'))
            ->body(__('accounts::filament/resources/tax-group/pages/edit-tax-group.notification.body'));
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make()
                ->action(function (TaxGroup $record): void {
                    try {
                        $record->delete();
                    } catch (QueryException) {
                        Notification::make()
                            ->danger()
                            ->title(__('accounts::filament/resources/tax-group/pages/edit-tax-group.header-actions.delete.notification.error.title'))
                            ->body(__('accounts::filament/resources/tax-group/pages/edit-tax-group.header-actions.delete.notification.error.body'))
                            ->send();
                    }
                })
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('accounts::filament/resources/tax-group/pages/edit-tax-group.header-actions.delete.notification.success.title'))
                        ->body(__('accounts::filament/resources/tax-group/pages/edit-tax-group.header-actions.delete.notification.success.body'))
                ),
        ];
    }
}
