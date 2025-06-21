<?php

declare(strict_types=1);

namespace Webkul\Security\Filament\Resources;

use BackedEnum;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Webkul\Security\Filament\Resources\TeamResource\Pages\ManageTeams;
use Webkul\Security\Models\Team;

class TeamResource extends Resource
{
    protected static ?string $model = Team::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static ?int $navigationSort = 3;

    public static function getNavigationLabel(): string
    {
        return __('security::filament/resources/team.navigation.title');
    }

    public static function getNavigationGroup(): string
    {
        return __('security::filament/resources/team.navigation.group');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
                TextInput::make('name')->label(__('security::filament/resources/team.form.fields.name'))
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label(__('security::filament/resources/team.table.columns.name'))
                    ->searchable()
                    ->limit(50)
                    ->sortable(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make()->successNotification(
                        Notification::make()->success()
                            ->title(__('security::filament/resources/team.table.actions.edit.notification.title'))
                            ->body(__('security::filament/resources/team.table.actions.edit.notification.body'))
                    ),
                DeleteAction::make()->successNotification(
                        Notification::make()->success()
                            ->title(__('security::filament/resources/team.table.actions.delete.notification.title'))
                            ->body(__('security::filament/resources/team.table.actions.delete.notification.body'))
                    ),
            ])
            ->emptyStateActions([
                CreateAction::make()->icon('heroicon-o-plus-circle')
                    ->successNotification(
                        Notification::make()->success()
                            ->title(__('security::filament/resources/team.table.empty-state-actions.create.notification.title'))
                            ->body(__('security::filament/resources/team.table.empty-state-actions.create.notification.body'))
                    ),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $schema
            ->schema([
                TextEntry::make('name')->icon('heroicon-o-user')
                    ->placeholder('—')
                    ->label(__('security::filament/resources/team.infolist.entries.name')),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageTeams::route('/'),
        ];
    }
}
