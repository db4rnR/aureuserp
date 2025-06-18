<?php

declare(strict_types=1);

namespace Webkul\Partner\Filament\Resources\PartnerResource\Pages;

use BackedEnum;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Webkul\Partner\Filament\Resources\PartnerResource;

final class ManageContacts extends ManageRelatedRecords
{
    protected static string $resource = PartnerResource::class;

    protected static string $relationship = 'contacts';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';

    public static function getNavigationLabel(): string
    {
        return __('partners::filament/resources/partner/pages/manage-contacts.title');
    }

    public function form(Schema $schema): Schema
    {
        return PartnerResource::form($schema);
    }

    public function table(Table $table): Table
    {
        return PartnerResource::table($table)
            ->headerActions([
                CreateAction::make()
                    ->label(__('partners::filament/resources/partner/pages/manage-contacts.table.header-actions.create.label'))
                    ->icon('heroicon-o-plus-circle')
                    ->mutateDataUsing(function (array $data): array {
                        $data['creator_id'] = Auth::id();

                        return $data;
                    })
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('partners::filament/resources/partner/pages/manage-contacts.table.header-actions.create.notification.title'))
                            ->body(__('partners::filament/resources/partner/pages/manage-contacts.table.header-actions.create.notification.body')),
                    ),
            ]);
    }
}
