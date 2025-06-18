<?php

declare(strict_types=1);

namespace Webkul\Product\Filament\Resources\AttributeResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Webkul\Product\Filament\Resources\AttributeResource;

final class ViewAttribute extends ViewRecord
{
    protected static string $resource = AttributeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('products::filament/resources/attribute/pages/view-attribute.header-actions.delete.notification.title'))
                        ->body(__('products::filament/resources/attribute/pages/view-attribute.header-actions.delete.notification.body')),
                ),
        ];
    }
}
