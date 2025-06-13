<?php

namespace Webkul\Partner\Filament\Resources\PartnerResource\RelationManagers;

use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Webkul\Partner\Filament\Resources\PartnerResource;

class ContactsRelationManager extends RelationManager
{
    protected static string $relationship = 'contacts';

    public function form(Form $form): Form
    {
        return PartnerResource::form($form);
    }

    public function table(Table $table): Table
    {
        return PartnerResource::table($table)
            ->filters([])
            ->groups([])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label(__('partners::filament/resources/partner/relation-managers/contacts.table.header-actions.create.label'))
                    ->icon('heroicon-o-plus-circle')
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['creator_id'] = Auth::id();

                        return $data;
                    })
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('partners::filament/resources/partner/relation-managers/contacts.table.header-actions.create.notification.title'))
                            ->body(__('partners::filament/resources/partner/relation-managers/contacts.table.header-actions.create.notification.body')),
                    ),
            ]);
    }
}
