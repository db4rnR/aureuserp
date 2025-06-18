<?php

declare(strict_types=1);

namespace Webkul\Sale\Filament\Clusters\Configuration\Resources\OrderTemplateProductResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Webkul\Sale\Filament\Clusters\Configuration\Resources\OrderTemplateProductResource;

final class ListOrderTemplateProducts extends ListRecords
{
    protected static string $resource = OrderTemplateProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
