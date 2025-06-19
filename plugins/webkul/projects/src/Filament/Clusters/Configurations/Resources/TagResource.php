<?php

declare(strict_types=1);

namespace Webkul\Project\Filament\Clusters\Configurations\Resources;

use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Webkul\Project\Filament\Clusters\Configurations;
use Webkul\Project\Filament\Clusters\Configurations\Resources\TagResource\Pages\ManageTags;
use Webkul\Project\Models\Tag;

class TagResource extends Resource
{
    protected static ?string $model = Tag::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-tag';

    protected static ?int $navigationSort = 4;

    protected static ?string $cluster = Configurations::class;

    public static function getNavigationLabel(): string
    {
        return __('projects::filament/clusters/configurations/resources/tag.navigation.title');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->components([
                TextInput::make('name')->label(__('projects::filament/clusters/configurations/resources/tag.form.name'))
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                ColorPicker::make('color')->default('#808080')
                    ->hexColor()
                    ->label(__('projects::filament/clusters/configurations/resources/tag.form.color')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label(__('projects::filament/clusters/configurations/resources/tag.table.columns.name'))
                    ->searchable()
                    ->sortable(),
                ColorColumn::make('color')->label(__('projects::filament/clusters/configurations/resources/tag.table.columns.color')),
            ])
            ->recordActions([
                EditAction::make()->hidden(fn ($record) => $record->trashed())
                    ->mutateDataUsing(function (array $data, Tag $record): array {
                        if (empty($data['color'])) {
                            $data['color'] = '#808080';
                        }

                        return $data;
                    })
                    ->successNotification(
                        Notification::make()->success()
                            ->title(__('projects::filament/clusters/configurations/resources/tag.table.actions.edit.notification.title'))
                            ->body(__('projects::filament/clusters/configurations/resources/tag.table.actions.edit.notification.body')),
                    ),
                RestoreAction::make()->successNotification(
                        Notification::make()->success()
                            ->title(__('projects::filament/clusters/configurations/resources/tag.table.actions.restore.notification.title'))
                            ->body(__('projects::filament/clusters/configurations/resources/tag.table.actions.restore.notification.body')),
                    ),
                DeleteAction::make()->successNotification(
                        Notification::make()->success()
                            ->title(__('projects::filament/clusters/configurations/resources/tag.table.actions.delete.notification.title'))
                            ->body(__('projects::filament/clusters/configurations/resources/tag.table.actions.delete.notification.body')),
                    ),
                ForceDeleteAction::make()->successNotification(
                        Notification::make()->success()
                            ->title(__('projects::filament/clusters/configurations/resources/tag.table.actions.force-delete.notification.title'))
                            ->body(__('projects::filament/clusters/configurations/resources/tag.table.actions.force-delete.notification.body')),
                    ),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    RestoreBulkAction::make()->successNotification(
                            Notification::make()->success()
                                ->title(__('projects::filament/clusters/configurations/resources/tag.table.bulk-actions.restore.notification.title'))
                                ->body(__('projects::filament/clusters/configurations/resources/tag.table.bulk-actions.restore.notification.body')),
                        ),
                    DeleteBulkAction::make()->successNotification(
                            Notification::make()->success()
                                ->title(__('projects::filament/clusters/configurations/resources/tag.table.bulk-actions.delete.notification.title'))
                                ->body(__('projects::filament/clusters/configurations/resources/tag.table.bulk-actions.delete.notification.body')),
                        ),
                    ForceDeleteBulkAction::make()->successNotification(
                            Notification::make()->success()
                                ->title(__('projects::filament/clusters/configurations/resources/tag.table.bulk-actions.force-delete.notification.title'))
                                ->body(__('projects::filament/clusters/configurations/resources/tag.table.bulk-actions.force-delete.notification.body')),
                        ),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageTags::route('/'),
        ];
    }
}
