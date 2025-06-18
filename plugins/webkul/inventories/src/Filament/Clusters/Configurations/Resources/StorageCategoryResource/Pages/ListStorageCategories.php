<?php

declare(strict_types=1);

namespace Webkul\Inventory\Filament\Clusters\Configurations\Resources\StorageCategoryResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;
use Webkul\Inventory\Filament\Clusters\Configurations\Resources\StorageCategoryResource;

final class ListStorageCategories extends ListRecords
{
    protected static string $resource = StorageCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label(__('inventories::filament/clusters/configurations/resources/storage-category/pages/list-storage-categories.header-actions.create.label'))
                ->icon('heroicon-o-plus-circle')
                ->mutateDataUsing(function (array $data) {
                    $user = Auth::user();

                    $data['creator_id'] = $user->id;

                    $data['company_id'] = $user->defaultCompany?->id;

                    return $data;
                })
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('inventories::filament/clusters/configurations/resources/storage-category/pages/list-storage-categories.header-actions.create.notification.title'))
                        ->body(__('inventories::filament/clusters/configurations/resources/storage-category/pages/list-storage-categories.header-actions.create.notification.body')),
                )
                ->successRedirectUrl(fn ($record): string => StorageCategoryResource::getUrl('edit', ['record' => $record])),
        ];
    }
}
