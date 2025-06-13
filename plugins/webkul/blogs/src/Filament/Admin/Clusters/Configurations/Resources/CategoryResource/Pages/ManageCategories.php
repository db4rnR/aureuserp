<?php

namespace Webkul\Blog\Filament\Admin\Clusters\Configurations\Resources\CategoryResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Facades\Auth;
use Webkul\Blog\Filament\Admin\Clusters\Configurations\Resources\CategoryResource;

class ManageCategories extends ManageRecords
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label(__('blogs::filament/admin/clusters/configurations/resources/category/pages/manage-categories.header-actions.create.label'))
                ->icon('heroicon-o-plus-circle')
                ->mutateFormDataUsing(function (array $data): array {
                    $data['creator_id'] = Auth::id();

                    return $data;
                })
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('blogs::filament/admin/clusters/configurations/resources/category/pages/manage-categories.header-actions.create.notification.title'))
                        ->body(__('blogs::filament/admin/clusters/configurations/resources/category/pages/manage-categories.header-actions.create.notification.body')),
                ),
        ];
    }
}
