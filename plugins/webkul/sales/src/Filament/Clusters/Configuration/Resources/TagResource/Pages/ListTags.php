<?php

declare(strict_types=1);

namespace Webkul\Sale\Filament\Clusters\Configuration\Resources\TagResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;
use Webkul\Sale\Filament\Clusters\Configuration\Resources\TagResource;

class ListTags extends ListRecords
{
    protected static string $resource = TagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->icon('heroicon-o-plus-circle')
                ->mutateDataUsing(function (array $data) {
                    $data['creator_id'] = Auth::id();

                    return $data;
                }),
        ];
    }
}
