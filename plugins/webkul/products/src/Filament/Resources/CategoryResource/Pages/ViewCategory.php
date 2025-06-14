<?php

namespace Webkul\Product\Filament\Resources\CategoryResource\Pages;

use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Database\QueryException;
use Webkul\Product\Filament\Resources\CategoryResource;
use Webkul\Product\Models\Category;

class ViewCategory extends ViewRecord
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            DeleteAction::make()
                ->action(function (DeleteAction $action, Category $record) {
                    try {
                        $record->delete();

                        $action->success();
                    } catch (QueryException $e) {
                        Notification::make()
                            ->danger()
                            ->title(__('products::filament/resources/category/pages/view-category.header-actions.delete.notification.error.title'))
                            ->body(__('products::filament/resources/category/pages/view-category.header-actions.delete.notification.error.body'))
                            ->send();

                        $action->failure();
                    }
                })
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('products::filament/resources/category/pages/view-category.header-actions.delete.notification.success.title'))
                        ->body(__('products::filament/resources/category/pages/view-category.header-actions.delete.notification.success.body')),
                ),
        ];
    }
}
