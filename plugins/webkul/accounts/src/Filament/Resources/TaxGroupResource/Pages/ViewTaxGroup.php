<?php

declare(strict_types=1);

namespace Webkul\Account\Filament\Resources\TaxGroupResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Database\QueryException;
use Webkul\Account\Filament\Resources\TaxGroupResource;
use Webkul\Account\Models\TaxGroup;

final class ViewTaxGroup extends ViewRecord
{
    protected static string $resource = TaxGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            DeleteAction::make()
                ->action(function (TaxGroup $record): void {
                    try {
                        $record->delete();
                    } catch (QueryException) {
                        Notification::make()
                            ->danger()
                            ->title(__('accounts::filament/resources/tax-group/pages/view-tax-group.header-actions.delete.notification.error.title'))
                            ->body(__('accounts::filament/resources/tax-group/pages/view-tax-group.header-actions.delete.notification.error.body'))
                            ->send();
                    }
                })
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('accounts::filament/resources/tax-group/pages/view-tax-group.header-actions.delete.notification.success.title'))
                        ->body(__('accounts::filament/resources/tax-group/pages/view-tax-group.header-actions.delete.notification.success.body'))
                ),
        ];
    }
}
