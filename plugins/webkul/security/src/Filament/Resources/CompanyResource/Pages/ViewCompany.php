<?php

declare(strict_types=1);

namespace Webkul\Security\Filament\Resources\CompanyResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Webkul\Security\Filament\Resources\CompanyResource;

class ViewCompany extends ViewRecord
{
    protected static string $resource = CompanyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            DeleteAction::make()->successNotification(
                    Notification::make()->success()
                        ->title(__('security::filament/resources/company/pages/view-company.header-actions.delete.notification.title'))
                        ->body(__('security::filament/resources/company/pages/view-company.header-actions.delete.notification.body'))
                ),
        ];
    }
}
