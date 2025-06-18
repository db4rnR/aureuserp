<?php

declare(strict_types=1);

namespace Z3d0X\FilamentFabricator\Resources\PageResource\Pages;

use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Z3d0X\FilamentFabricator\Facades\FilamentFabricator;
use Z3d0X\FilamentFabricator\Models\Contracts\Page as PageContract;
use Z3d0X\FilamentFabricator\Resources\PageResource;

final class ViewPage extends ViewRecord
{
    protected static string $resource = PageResource::class;

    public static function getResource(): string
    {
        return config('filament-fabricator.page-resource') ?? self::$resource;
    }

    protected function getActions(): array
    {
        return [
            EditAction::make(),

            Action::make('visit')
                ->label(__('filament-fabricator::page-resource.actions.visit'))
                ->url(function () {
                    /** @var PageContract $page */
                    $page = $this->getRecord();

                    return FilamentFabricator::getPageUrlFromId($page->id);
                })
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->openUrlInNewTab()
                ->color('success')
                ->visible(config('filament-fabricator.routing.enabled')),
        ];
    }
}
