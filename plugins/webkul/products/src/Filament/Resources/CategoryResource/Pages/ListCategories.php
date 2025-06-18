<?php

declare(strict_types=1);

namespace Webkul\Product\Filament\Resources\CategoryResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;
use Webkul\Product\Filament\Resources\CategoryResource;

final class ListCategories extends ListRecords
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label(__('products::filament/resources/category/pages/list-categories.header-actions.create.label'))
                ->icon('heroicon-o-plus-circle')
                ->mutateDataUsing(function (array $data) {
                    $user = Auth::user();

                    $data['creator_id'] = $user->id;

                    $data['company_id'] = $user->default_company_id;

                    return $data;
                })
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('products::filament/resources/category/pages/list-categories.header-actions.create.notification.title'))
                        ->body(__('products::filament/resources/category/pages/list-categories.header-actions.create.notification.body')),
                ),
        ];
    }
}
