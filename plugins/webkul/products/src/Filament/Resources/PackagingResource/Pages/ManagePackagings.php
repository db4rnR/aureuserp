<?php

namespace Webkul\Product\Filament\Resources\PackagingResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Facades\Auth;
use Webkul\Product\Filament\Resources\PackagingResource;

class ManagePackagings extends ManageRecords
{
    protected static string $resource = PackagingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label(__('products::filament/resources/packaging/pages/manage-packagings.header-actions.create.label'))
                ->icon('heroicon-o-plus-circle')
                ->mutateFormDataUsing(function (array $data): array {
                    $data['creator_id'] = Auth::id();

                    return $data;
                })
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('products::filament/resources/packaging/pages/manage-packagings.header-actions.create.notification.title'))
                        ->body(__('products::filament/resources/packaging/pages/manage-packagings.header-actions.create.notification.body')),
                ),
        ];
    }
}
