<?php

namespace Webkul\Inventory\Filament\Clusters\Operations\Resources\InternalResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Support\Htmlable;
use Webkul\Inventory\Filament\Clusters\Operations\Resources\InternalResource;
use Webkul\Inventory\Filament\Clusters\Operations\Resources\OperationResource;
use Webkul\TableViews\Filament\Concerns\HasTableViews;

class ListInternals extends ListRecords
{
    use HasTableViews;

    protected static string $resource = InternalResource::class;

    public function getTitle(): string|Htmlable
    {
        return __('inventories::filament/clusters/operations/resources/internal.navigation.title');
    }

    public function getPresetTableViews(): array
    {
        return OperationResource::getPresetTableViews();
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label(__('inventories::filament/clusters/operations/resources/internal/pages/list-internals.header-actions.create.label'))
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
