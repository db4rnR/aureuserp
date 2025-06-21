<?php

declare(strict_types=1);

namespace Webkul\Employee\Traits\Resources\Employee;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\Section;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Webkul\Employee\Models\SkillType;
use Webkul\Support\Filament\Tables as CustomTables;

trait EmployeeSkillRelation
{
    public function form(Form $form): Form
    {
        return $form
            ->components([
                Section::make([
                    Hidden::make('creator_id')->default(fn () => Auth::user()->id),
                    Radio::make('skill_type_id')->label(__('employees::filament/resources/employee/relation-manager/skill.form.sections.fields.skill-type'))
                        ->options(SkillType::pluck('name', 'id'))
                        ->default(fn () => SkillType::first()?->id)
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(fn (callable $set) => $set('skill_id', null)),
                    Group::make()->schema([
                            Select::make('skill_id')->label(__('employees::filament/resources/employee/relation-manager/skill.form.sections.fields.skill'))
                                ->options(
                                    fn (callable $get) => SkillType::find($get('skill_type_id'))?->skills->pluck('name', 'id') ?? []
                                )
                                ->required()
                                ->reactive()
                                ->afterStateUpdated(fn (callable $set) => $set('skill_level_id', null)),
                            Select::make('skill_level_id')->label(__('employees::filament/resources/employee/relation-manager/skill.form.sections.fields.skill-level'))
                                ->options(
                                    fn (callable $get) => SkillType::find($get('skill_type_id'))?->skillLevels->pluck('name', 'id') ?? []
                                )
                                ->required(),
                        ]),
                ])->columns(2),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('skillType.name')->label(__('employees::filament/resources/employee/relation-manager/skill.table.columns.skill-type'))
                    ->sortable(),
                TextColumn::make('skill.name')->label(__('employees::filament/resources/employee/relation-manager/skill.table.columns.skill'))
                    ->sortable(),
                TextColumn::make('skillLevel.name')->label(__('employees::filament/resources/employee/relation-manager/skill.table.columns.skill-level'))
                    ->badge()
                    ->color(fn ($record) => $record->skillType?->color),
                CustomTables\Columns\ProgressBarEntry::make('skillLevel.level')->getStateUsing(fn ($record) => $record->skillLevel?->level)
                    ->color(function ($record): string {
                        if ($record->skillLevel?->level === 100) {
                            return 'success';
                        }
                        if ($record->skillLevel?->level >= 50 && $record->skillLevel?->level < 80) {
                            return 'warning';
                        }
                        if ($record->skillLevel?->level < 20) {
                            return 'danger';
                        }

                        return 'info';

                    })
                    ->label(__('employees::filament/resources/employee/relation-manager/skill.table.columns.level-percent')),
                TextColumn::make('creator.name')->label(__('employees::filament/resources/employee/relation-manager/skill.table.columns.created-by'))
                    ->sortable(),
                TextColumn::make('user.name')->label(__('employees::filament/resources/employee/relation-manager/skill.table.columns.user'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('created_at')->label(__('employees::filament/resources/employee/relation-manager/skill.table.columns.created-at'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->date(),
            ])
            ->groups([
                Tables\Grouping\Group::make('skillType.name')->label(__('employees::filament/resources/employee/relation-manager/skill.table.groups.skill-type'))
                    ->collapsible(),
            ])
            ->filters([])
            ->headerActions([
                CreateAction::make()->label(__('employees::filament/resources/employee/relation-manager/skill.table.header-actions.add-skill'))
                    ->icon('heroicon-o-plus-circle')
                    ->successNotification(
                        Notification::make()->success()
                            ->title(__('employees::filament/resources/employee/relation-manager/skill.table.actions.create.notification.title'))
                            ->body(__('employees::filament/resources/employee/relation-manager/skill.table.actions.create.notification.body'))
                    ),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make()->successNotification(
                        Notification::make()->success()
                            ->title(__('employees::filament/resources/employee/relation-manager/skill.table.actions.edit.notification.title'))
                            ->body(__('employees::filament/resources/employee/relation-manager/skill.table.actions.edit.notification.body'))
                    ),
                DeleteAction::make()->successNotification(
                        Notification::make()->success()
                            ->title(__('employees::filament/resources/employee/relation-manager/skill.table.actions.delete.notification.title'))
                            ->body(__('employees::filament/resources/employee/relation-manager/skill.table.actions.delete.notification.body'))
                    ),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->successNotification(
                            Notification::make()->success()
                                ->title(__('employees::filament/resources/employee/relation-manager/skill.table.bulk-actions.delete.notification.title'))
                                ->body(__('employees::filament/resources/employee/relation-manager/skill.table.bulk-actions.delete.notification.body'))
                        ),
                ]),
            ]);
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->components([
                Group::make()->schema([
                        Group::make()->schema([
                                TextEntry::make('skillType.name')->placeholder('—')
                                    ->label(__('employees::filament/resources/employee/relation-manager/skill.infolist.entries.skill-type')),
                                TextEntry::make('skill.name')->placeholder('—')
                                    ->label(__('employees::filament/resources/employee/relation-manager/skill.infolist.entries.skill')),
                                TextEntry::make('skillLevel.name')->placeholder('—')
                                    ->badge()
                                    ->color(fn ($record) => $record->skillType?->color)
                                    ->label(__('employees::filament/resources/employee/relation-manager/skill.infolist.entries.skill-level')),
                                CustomTables\Infolists\ProgressBarEntry::make('skillLevel.level')->getStateUsing(fn ($record) => $record->skillLevel?->level)
                                    ->color(function ($record): string {
                                        if ($record->skillLevel->level === 100) {
                                            return 'success';
                                        }
                                        if ($record->skillLevel->level >= 50 && $record->skillLevel->level < 80) {
                                            return 'warning';
                                        }
                                        if ($record->skillLevel->level < 20) {
                                            return 'danger';
                                        }

                                        return 'info';

                                    })
                                    ->label(__('employees::filament/resources/employee/relation-manager/skill.infolist.entries.level-percent')),
                            ])
                            ->columns(2),
                    ])
                    ->columnSpan('full'),
            ]);
    }
}
