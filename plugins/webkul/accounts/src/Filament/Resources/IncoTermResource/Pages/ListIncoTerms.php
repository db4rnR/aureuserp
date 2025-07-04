<?php

declare(strict_types=1);

namespace Webkul\Account\Filament\Resources\IncoTermResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Webkul\Account\Filament\Resources\IncoTermResource;

class ListIncoTerms extends ListRecords
{
    protected static string $resource = IncoTermResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->icon('heroicon-o-plus-circle')
                ->successNotification(
                    Notification::make()->success()
                        ->title(__('accounts::filament/resources/inco-term/pages/list-inco-term.header-actions.notification.title'))
                        ->body(__('accounts::filament/resources/inco-term/pages/list-inco-term.header-actions.notification.body'))
                ),
        ];
    }
}
