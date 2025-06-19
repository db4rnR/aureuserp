<?php

declare(strict_types=1);

namespace Webkul\Inventory\Filament\Clusters\Products\Resources\PackageResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Webkul\Inventory\Enums\LocationType;
use Webkul\Inventory\Filament\Clusters\Products\Resources\PackageResource;
use Webkul\TableViews\Filament\Components\PresetView;
use Webkul\TableViews\Filament\Concerns\HasTableViews;

class ListPackages extends ListRecords
{
    use HasTableViews;

    protected static string $resource = PackageResource::class;

    public function getPresetTableViews(): array
    {
        return [
            'internal_locations' => PresetView::make(__('inventories::filament/clusters/products/resources/package/pages/list-packages.tabs.internal'))
                ->favorite()
                ->default()
                ->icon('heroicon-s-map-pin')
                ->modifyQueryUsing(fn ($query) => $query->whereHas('location', function (Builder $query): void {
                    $query->where('type', LocationType::INTERNAL);
                })),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label(__('inventories::filament/clusters/products/resources/package/pages/list-packages.header-actions.create.label'))
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
