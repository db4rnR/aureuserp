<?php

declare(strict_types=1);

namespace Webkul\Partner\Filament\Resources\PartnerResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;
use Webkul\Chatter\Filament\Actions\ChatterAction;
use Webkul\Partner\Filament\Resources\PartnerResource;

final class ViewPartner extends ViewRecord
{
    protected static string $resource = PartnerResource::class;

    public function getTitle(): string|Htmlable
    {
        return __('partners::filament/resources/partner/pages/view-partner.title');
    }

    protected function getHeaderActions(): array
    {
        return [
            ChatterAction::make()
                ->setResource(self::$resource),
            EditAction::make(),
            DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('partners::filament/resources/partner/pages/view-partner.header-actions.delete.notification.title'))
                        ->body(__('partners::filament/resources/partner/pages/view-partner.header-actions.delete.notification.body')),
                ),
        ];
    }
}
