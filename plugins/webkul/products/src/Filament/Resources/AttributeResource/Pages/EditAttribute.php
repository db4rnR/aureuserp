<?php

namespace Webkul\Product\Filament\Resources\AttributeResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Webkul\Product\Filament\Resources\AttributeResource;

class EditAttribute extends EditRecord
{
    protected static string $resource = AttributeResource::class;

    protected function getSavedNotification(): Notification
    {
        return Notification::make()
            ->success()
            ->title(__('products::filament/resources/attribute/pages/edit-attribute.notification.title'))
            ->body(__('products::filament/resources/attribute/pages/edit-attribute.notification.body'));
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('products::filament/resources/attribute/pages/edit-attribute.header-actions.delete.notification.title'))
                        ->body(__('products::filament/resources/attribute/pages/edit-attribute.header-actions.delete.notification.body')),
                ),
        ];
    }
}
