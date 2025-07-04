<?php

declare(strict_types=1);

namespace Webkul\Project\Filament\Resources;

use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Resources\Pages\Page;
use Filament\Resources\RelationManagers\RelationGroup;
use Filament\Resources\Resource;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\Section;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\QueryBuilder\Constraints\DateConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\NumberConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint\Operators\IsRelatedToOperator;
use Filament\Tables\Filters\QueryBuilder\Constraints\SelectConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\TextConstraint;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Webkul\Field\Filament\Forms\Components\ProgressStepper;
use Webkul\Field\Filament\Traits\HasCustomFields;
use Webkul\Partner\Filament\Resources\PartnerResource;
use Webkul\Project\Enums\TaskState;
use Webkul\Project\Filament\Resources\ProjectResource\Pages\ManageTasks;
use Webkul\Project\Filament\Resources\TaskResource\Pages\CreateTask;
use Webkul\Project\Filament\Resources\TaskResource\Pages\EditTask;
use Webkul\Project\Filament\Resources\TaskResource\Pages\ListTasks;
use Webkul\Project\Filament\Resources\TaskResource\Pages\ManageSubTasks;
use Webkul\Project\Filament\Resources\TaskResource\Pages\ManageTimesheets;
use Webkul\Project\Filament\Resources\TaskResource\Pages\ViewTask;
use Webkul\Project\Filament\Resources\TaskResource\RelationManagers\SubTasksRelationManager;
use Webkul\Project\Filament\Resources\TaskResource\RelationManagers\TimesheetsRelationManager;
use Webkul\Project\Models\Project;
use Webkul\Project\Models\Task;
use Webkul\Project\Models\TaskStage;
use Webkul\Project\Settings\TaskSettings;
use Webkul\Project\Settings\TimeSettings;
use Webkul\Security\Filament\Resources\UserResource;
use Webkul\Support\Filament\Tables\Columns\ProgressBarEntry;

class TaskResource extends Resource
{
    use HasCustomFields;

    protected static ?string $model = Task::class;

    protected static ?string $slug = 'project/tasks';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    protected static ?string $recordTitleAttribute = 'title';

    public static function getNavigationLabel(): string
    {
        return __('projects::filament/resources/task.navigation.title');
    }

    public static function getNavigationGroup(): string
    {
        return __('projects::filament/resources/task.navigation.group');
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'project.name', 'partner.name', 'milestone.name'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            __('projects::filament/resources/task.global-search.project') => $record->project?->name ?? '—',
            __('projects::filament/resources/task.global-search.customer') => $record->partner?->name ?? '—',
            __('projects::filament/resources/task.global-search.milestone') => $record->milestone?->name ?? '—',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->components([
                Group::make()->schema([
                        ProgressStepper::make('stage_id')->hiddenLabel()
                            ->inline()
                            ->required()
                            ->options(fn () => TaskStage::orderBy('sort')->get()->mapWithKeys(fn ($stage) => [$stage->id => $stage->name]))
                            ->default(TaskStage::first()?->id),
                        Section::make(__('projects::filament/resources/task.form.sections.general.title'))
                            ->schema([
                                TextInput::make('title')->label(__('projects::filament/resources/task.form.sections.general.fields.title'))
                                    ->required()
                                    ->maxLength(255)
                                    ->autofocus()
                                    ->placeholder(__('projects::filament/resources/task.form.sections.general.fields.title-placeholder'))
                                    ->extraInputAttributes(['style' => 'font-size: 1.5rem;height: 3rem;']),
                                ToggleButtons::make('state')->required()
                                    ->default(TaskState::IN_PROGRESS)
                                    ->inline()
                                    ->options(TaskState::options())
                                    ->colors(TaskState::colors())
                                    ->icons(TaskState::icons()),
                                Select::make('tags')->label(__('projects::filament/resources/task.form.sections.general.fields.tags'))
                                    ->relationship(name: 'tags', titleAttribute: 'name')
                                    ->multiple()
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm([
                                        Group::make()->schema([
                                                TextInput::make('name')->label(__('projects::filament/resources/task.form.sections.general.fields.name'))
                                                    ->required()
                                                    ->maxLength(255)
                                                    ->unique('projects_tags'),
                                                ColorPicker::make('color')->default('#808080')
                                                    ->hexColor()
                                                    ->label(__('projects::filament/resources/task.form.sections.general.fields.color')),
                                            ])->columns(2),
                                    ]),
                                RichEditor::make('description')->label(__('projects::filament/resources/task.form.sections.general.fields.description')),
                            ]),

                        Section::make(__('projects::filament/resources/task.form.sections.additional.title'))
                            ->visible(($customFormFields = self::getCustomFormFields()) !== [])
                            ->schema($customFormFields),
                    ])
                    ->columnSpan(['lg' => 2]),

                Group::make()->schema([
                        Section::make(__('projects::filament/resources/task.form.sections.settings.title'))
                            ->schema([
                                Select::make('project_id')->label(__('projects::filament/resources/task.form.sections.settings.fields.project'))
                                    ->relationship('project', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->live()
                                    ->createOptionForm(fn (Schema $form): Schema => ProjectResource::form($form))
                                    ->afterStateUpdated(function (Set $set): void {
                                        $set('milestone_id', null);
                                    }),
                                Select::make('milestone_id')->label(__('projects::filament/resources/task.form.sections.settings.fields.milestone'))
                                    ->relationship(
                                        name: 'milestone',
                                        titleAttribute: 'name',
                                        modifyQueryUsing: fn (Get $get, Builder $query) => $query->where('project_id', $get('project_id')),
                                    )
                                    ->searchable()
                                    ->preload()
                                    ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('projects::filament/resources/task.form.sections.settings.fields.milestone-hint-text'))
                                    ->createOptionForm(fn ($get): array => [
                                        TextInput::make('name')->label(__('projects::filament/resources/task.form.sections.settings.fields.name'))
                                            ->required()
                                            ->maxLength(255),
                                        DateTimePicker::make('deadline')->label(__('projects::filament/resources/task.form.sections.settings.fields.deadline'))
                                            ->native(false)
                                            ->suffixIcon('heroicon-o-clock'),
                                        Toggle::make('is_completed')->label(__('projects::filament/resources/task.form.sections.settings.fields.is-completed'))
                                            ->required(),
                                        Hidden::make('project_id')->default($get('project_id')),
                                        Hidden::make('creator_id')->default(fn () => Auth::user()->id),
                                    ])
                                    ->hidden(function (TaskSettings $taskSettings, Get $get): bool {
                                        $project = Project::find($get('project_id'));

                                        if (! $project) {
                                            return true;
                                        }

                                        if (! $taskSettings->enable_milestones) {
                                            return true;
                                        }

                                        return ! $project->allow_milestones;
                                    })
                                    ->visible(fn (TaskSettings $taskSettings): bool => $taskSettings->enable_milestones),
                                Select::make('partner_id')->label(__('projects::filament/resources/task.form.sections.settings.fields.customer'))
                                    ->relationship('partner', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm(fn (Schema $form): Schema => PartnerResource::form($form))
                                    ->editOptionForm(fn (Schema $form): Schema => PartnerResource::form($form)),
                                Select::make('users')->label(__('projects::filament/resources/task.form.sections.settings.fields.assignees'))
                                    ->relationship('users', 'name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload()
                                    ->createOptionForm(fn (Schema $form): Schema => UserResource::form($form)),
                                DateTimePicker::make('deadline')->label(__('projects::filament/resources/task.form.sections.settings.fields.deadline'))
                                    ->native(false)
                                    ->suffixIcon('heroicon-o-calendar'),
                                TextInput::make('allocated_hours')->label(__('projects::filament/resources/task.form.sections.settings.fields.allocated-hours'))
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(99999999999)
                                    ->suffixIcon('heroicon-o-clock')
                                    ->helperText(__('projects::filament/resources/task.form.sections.settings.fields.allocated-hours-helper-text'))
                                    ->dehydrateStateUsing(fn ($state) => $state ?: 0)
                                    ->visible(fn (TimeSettings $timeSettings): bool => $timeSettings->enable_timesheets),
                            ]),
                    ]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        $isTimesheetEnabled = app(TimeSettings::class)->enable_timesheets;

        return $table
            ->columns(self::mergeCustomTableColumns([
                TextColumn::make('id')->label(__('projects::filament/resources/task.table.columns.id'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('priority')->label(__('projects::filament/resources/task.table.columns.priority'))
                    ->icon(fn (Task $record): string => $record->priority ? 'heroicon-s-star' : 'heroicon-o-star')
                    ->color(fn (Task $record): string => $record->priority ? 'warning' : 'gray')
                    ->action(function (Task $record): void {
                        $record->update([
                            'priority' => ! $record->priority,
                        ]);
                    }),
                IconColumn::make('state')->label(__('projects::filament/resources/task.table.columns.state'))
                    ->sortable()
                    ->toggleable()
                    ->icon(fn (string $state): string => TaskState::icons()[$state])
                    ->color(fn (string $state): string => TaskState::colors()[$state])
                    ->tooltip(fn (string $state): string => TaskState::options()[$state])
                    ->action(
                        Action::make('updateState')->modalHeading('Update Task State')
                            ->schema(fn (Task $record): array => [
                                ToggleButtons::make('state')->label(__('projects::filament/resources/task.table.columns.new-state'))
                                    ->required()
                                    ->default($record->state)
                                    ->inline()
                                    ->options(TaskState::options())
                                    ->colors(TaskState::colors())
                                    ->icons(TaskState::icons()),
                            ])
                            ->modalSubmitActionLabel(__('projects::filament/resources/task.table.columns.update-state'))
                            ->action(function (Task $record, array $data): void {
                                $record->update([
                                    'state' => $data['state'],
                                ]);
                            })
                    ),
                TextColumn::make('title')->label(__('projects::filament/resources/task.table.columns.title'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('project.name')->label(__('projects::filament/resources/task.table.columns.project'))
                    ->hiddenOn(ManageTasks::class)
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->placeholder(__('projects::filament/resources/task.table.columns.project-placeholder')),
                TextColumn::make('milestone.name')->label(__('projects::filament/resources/task.table.columns.milestone'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->visible(fn (TaskSettings $taskSettings): bool => $taskSettings->enable_milestones),
                TextColumn::make('partner.name')->label(__('projects::filament/resources/task.table.columns.customer'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('users.name')->label(__('projects::filament/resources/task.table.columns.assignees'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('allocated_hours')->label(__('projects::filament/resources/task.table.columns.allocated-time'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->formatStateUsing(function ($state): string {
                        $hours = floor($state);
                        $minutes = ($state - $hours) * 60;

                        return $hours.':'.$minutes;
                    })
                    ->summarize(
                        Sum::make()->label(__('projects::filament/resources/task.table.columns.allocated-time'))
                            ->numeric()
                            ->formatStateUsing(function ($state): string {
                                $hours = floor($state);
                                $minutes = ($state - $hours) * 60;

                                return $hours.':'.$minutes;
                            })
                    )
                    ->visible(fn (TimeSettings $timeSettings): bool => $timeSettings->enable_timesheets),
                TextColumn::make('total_hours_spent')->label(__('projects::filament/resources/task.table.columns.time-spent'))
                    ->sortable()
                    ->toggleable()
                    ->numeric()
                    ->formatStateUsing(function ($state): string {
                        $hours = floor($state);
                        $minutes = ($state - $hours) * 60;

                        return $hours.':'.$minutes;
                    })
                    ->summarize(
                        Sum::make()->label(__('projects::filament/resources/task.table.columns.time-spent'))
                            ->numeric()
                            ->formatStateUsing(function ($state): string {
                                $hours = floor($state);
                                $minutes = ($state - $hours) * 60;

                                return $hours.':'.$minutes;
                            })
                    )
                    ->visible(fn (TimeSettings $timeSettings): bool => $timeSettings->enable_timesheets),
                TextColumn::make('remaining_hours')->label(__('projects::filament/resources/task.table.columns.time-remaining'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->formatStateUsing(function ($state): string {
                        $hours = floor($state);
                        $minutes = ($state - $hours) * 60;

                        return $hours.':'.$minutes;
                    })
                    ->summarize(
                        Sum::make()->label(__('projects::filament/resources/task.table.columns.time-remaining'))
                            ->numeric()
                            ->numeric()
                            ->formatStateUsing(function ($state): string {
                                $hours = floor($state);
                                $minutes = ($state - $hours) * 60;

                                return $hours.':'.$minutes;
                            })
                    )
                    ->visible(fn (TimeSettings $timeSettings): bool => $timeSettings->enable_timesheets),
                ProgressBarEntry::make('progress')->label(__('projects::filament/resources/task.table.columns.progress'))
                    ->sortable()
                    ->toggleable()
                    ->color(fn (Task $record): string => $record->progress > 100 ? 'danger' : ($record->progress < 100 ? 'warning' : 'success'))
                    ->visible(fn (TimeSettings $timeSettings): bool => $timeSettings->enable_timesheets),
                TextColumn::make('deadline')->label(__('projects::filament/resources/task.table.columns.deadline'))
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('tags.name')->label(__('projects::filament/resources/task.table.columns.tags'))
                    ->badge()
                    ->state(fn (Task $record): array => $record->tags()->get()->map(fn ($tag): array => [
                        'label' => $tag->name,
                        'color' => $tag->color ?? '#808080',
                    ])->toArray())
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state['label'])
                    ->color(fn ($state): array => Color::generateV3Palette($state['color']))
                    ->toggleable(),
                TextColumn::make('stage.name')->label(__('projects::filament/resources/task.table.columns.stage'))
                    ->sortable()
                    ->toggleable(),
            ]))
            ->groups([
                Tables\Grouping\Group::make('state')->label(__('projects::filament/resources/task.table.groups.state'))
                    ->getTitleFromRecordUsing(fn (Task $record): string => TaskState::options()[$record->state]),
                Tables\Grouping\Group::make('project.name')->label(__('projects::filament/resources/task.table.groups.project')),
                Tables\Grouping\Group::make('deadline')->label(__('projects::filament/resources/task.table.groups.deadline'))
                    ->date(),
                Tables\Grouping\Group::make('stage.name')->label(__('projects::filament/resources/task.table.groups.stage')),
                Tables\Grouping\Group::make('milestone.name')->label(__('projects::filament/resources/task.table.groups.milestone')),
                Tables\Grouping\Group::make('partner.name')->label(__('projects::filament/resources/task.table.groups.customer')),
                Tables\Grouping\Group::make('created_at')->label(__('projects::filament/resources/task.table.groups.created-at'))
                    ->date(),
            ])
            ->reorderable('sort')
            ->defaultSort('sort', 'desc')
            ->filters([
                QueryBuilder::make()->constraints(collect(self::mergeCustomTableQueryBuilderConstraints([
                        TextConstraint::make('title')->label(__('projects::filament/resources/task.table.filters.title')),
                        SelectConstraint::make('priority')->label(__('projects::filament/resources/task.table.filters.priority'))
                            ->options([
                                0 => __('projects::filament/resources/task.table.filters.low'),
                                1 => __('projects::filament/resources/task.table.filters.high'),
                            ])
                            ->icon('heroicon-o-star'),
                        SelectConstraint::make('state')->label(__('projects::filament/resources/task.table.filters.state'))
                            ->multiple()
                            ->options(TaskState::options())
                            ->icon('heroicon-o-bars-2'),
                        RelationshipConstraint::make('tags')->label(__('projects::filament/resources/task.table.filters.tags'))
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            )
                            ->icon('heroicon-o-tag'),
                        $isTimesheetEnabled
                            ? NumberConstraint::make('allocated_hours')->label(__('projects::filament/resources/task.table.filters.allocated-hours'))
                                ->icon('heroicon-o-clock')
                            : null,
                        $isTimesheetEnabled
                            ? NumberConstraint::make('total_hours_spent')->label(__('projects::filament/resources/task.table.filters.total-hours-spent'))
                                ->icon('heroicon-o-clock')
                            : null,
                        $isTimesheetEnabled
                            ? NumberConstraint::make('remaining_hours')->label(__('projects::filament/resources/task.table.filters.remaining-hours'))
                                ->icon('heroicon-o-clock')
                            : null,
                        $isTimesheetEnabled
                            ? NumberConstraint::make('overtime')->label(__('projects::filament/resources/task.table.filters.overtime'))
                                ->icon('heroicon-o-clock')
                            : null,
                        $isTimesheetEnabled
                            ? NumberConstraint::make('progress')->label(__('projects::filament/resources/task.table.filters.progress'))
                                ->icon('heroicon-o-bars-2')
                            : null,
                        DateConstraint::make('deadline')->label(__('projects::filament/resources/task.table.filters.deadline'))
                            ->icon('heroicon-o-calendar'),
                        DateConstraint::make('created_at')->label(__('projects::filament/resources/task.table.filters.created-at')),
                        DateConstraint::make('updated_at')->label(__('projects::filament/resources/task.table.filters.updated-at')),
                        RelationshipConstraint::make('users')->label(__('projects::filament/resources/task.table.filters.assignees'))
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            )
                            ->icon('heroicon-o-users'),
                        RelationshipConstraint::make('partner')->label(__('projects::filament/resources/task.table.filters.customer'))
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            )
                            ->icon('heroicon-o-user'),
                        RelationshipConstraint::make('project')->label(__('projects::filament/resources/task.table.filters.project'))
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            )
                            ->icon('heroicon-o-folder'),
                        RelationshipConstraint::make('stage')->label(__('projects::filament/resources/task.table.filters.stage'))
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            )
                            ->icon('heroicon-o-bars-2'),
                        RelationshipConstraint::make('milestone')->label(__('projects::filament/resources/task.table.filters.milestone'))
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            )
                            ->icon('heroicon-o-flag'),
                        RelationshipConstraint::make('company')->label(__('projects::filament/resources/task.table.filters.company'))
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            )
                            ->icon('heroicon-o-building-office'),
                        RelationshipConstraint::make('creator')->label(__('projects::filament/resources/task.table.filters.creator'))
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            )
                            ->icon('heroicon-o-user'),
                    ]))->filter()->values()->all()),
            ], layout: FiltersLayout::Modal)
            ->filtersTriggerAction(
                fn (Action $action): Action => $action
                    ->slideOver(),
            )
            ->filtersFormColumns(2)
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()->hidden(fn ($record) => $record->trashed()),
                    EditAction::make()->hidden(fn ($record) => $record->trashed()),
                    RestoreAction::make()->successNotification(
                            Notification::make()->success()
                                ->title(__('projects::filament/resources/task.table.actions.restore.notification.title'))
                                ->body(__('projects::filament/resources/task.table.actions.restore.notification.body')),
                        ),
                    DeleteAction::make()->successNotification(
                            Notification::make()->success()
                                ->title(__('projects::filament/resources/task.table.actions.delete.notification.title'))
                                ->body(__('projects::filament/resources/task.table.actions.delete.notification.body')),
                        ),
                    ForceDeleteAction::make()->successNotification(
                            Notification::make()->success()
                                ->title(__('projects::filament/resources/task.table.actions.force-delete.notification.title'))
                                ->body(__('projects::filament/resources/task.table.actions.force-delete.notification.body')),
                        ),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    RestoreBulkAction::make()->successNotification(
                            Notification::make()->success()
                                ->title(__('projects::filament/resources/task.table.bulk-actions.restore.notification.title'))
                                ->body(__('projects::filament/resources/task.table.bulk-actions.restore.notification.body')),
                        ),
                    DeleteBulkAction::make()->successNotification(
                            Notification::make()->success()
                                ->title(__('projects::filament/resources/task.table.bulk-actions.delete.notification.title'))
                                ->body(__('projects::filament/resources/task.table.bulk-actions.delete.notification.body')),
                        ),
                    ForceDeleteBulkAction::make()->successNotification(
                            Notification::make()->success()
                                ->title(__('projects::filament/resources/task.table.bulk-actions.force-delete.notification.title'))
                                ->body(__('projects::filament/resources/task.table.bulk-actions.force-delete.notification.body')),
                        ),
                ]),
            ])
            ->checkIfRecordIsSelectableUsing(
                fn (Model $record): bool => self::can('delete', $record) || self::can('forceDelete', $record) || self::can('restore', $record),
            );
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->components([
                Group::make()->schema([
                        Section::make(__('projects::filament/resources/task.infolist.sections.general.title'))
                            ->schema([
                                TextEntry::make('title')->label(__('projects::filament/resources/task.infolist.sections.general.entries.title'))
                                    ->size(TextSize::Large)
                                    ->weight(FontWeight::Bold),

                                TextEntry::make('state')->label(__('projects::filament/resources/task.infolist.sections.general.entries.state'))
                                    ->badge()
                                    ->icon(fn (string $state): string => TaskState::icons()[$state])
                                    ->color(fn (string $state): string => TaskState::colors()[$state])
                                    ->formatStateUsing(fn (string $state): string => TaskState::options()[$state]),

                                IconEntry::make('priority')->label(__('projects::filament/resources/task.infolist.sections.general.entries.priority'))
                                    ->icon(fn ($record): string => $record->priority ? 'heroicon-s-star' : 'heroicon-o-star')
                                    ->color(fn ($record): string => $record->priority ? 'warning' : 'gray'),

                                TextEntry::make('description')->label(__('projects::filament/resources/task.infolist.sections.general.entries.description'))
                                    ->html(),

                                TextEntry::make('tags.name')->label(__('projects::filament/resources/task.infolist.sections.general.entries.tags'))
                                    ->badge()
                                    ->state(fn (Task $record): array => $record->tags()->get()->map(fn ($tag): array => [
                                        'label' => $tag->name,
                                        'color' => $tag->color ?? '#808080',
                                    ])->toArray())
                                    ->badge()
                                    ->formatStateUsing(fn ($state) => $state['label'])
                                    ->color(fn ($state): array => Color::generateV3Palette($state['color']))
                                    ->listWithLineBreaks()
                                    ->separator(', '),
                            ]),

                        Section::make(__('projects::filament/resources/task.infolist.sections.project-information.title'))
                            ->schema([
                                Grid::make(2)->schema([
                                        TextEntry::make('project.name')->label(__('projects::filament/resources/task.infolist.sections.project-information.entries.project'))
                                            ->icon('heroicon-o-folder')
                                            ->placeholder('—')
                                            ->color('primary')
                                            ->url(fn (Task $record): string => $record->project_id ? ProjectResource::getUrl('view', ['record' => $record->project]) : '#'),

                                        TextEntry::make('milestone.name')->label(__('projects::filament/resources/task.infolist.sections.project-information.entries.milestone'))
                                            ->icon('heroicon-o-flag')
                                            ->placeholder('—')
                                            ->visible(fn (TaskSettings $taskSettings): bool => $taskSettings->enable_milestones),

                                        TextEntry::make('stage.name')->label(__('projects::filament/resources/task.infolist.sections.project-information.entries.stage'))
                                            ->icon('heroicon-o-queue-list')
                                            ->badge(),

                                        TextEntry::make('partner.name')->label(__('projects::filament/resources/task.infolist.sections.project-information.entries.customer'))
                                            ->icon('heroicon-o-queue-list')
                                            ->icon('heroicon-o-phone')
                                            ->listWithLineBreaks()
                                            ->placeholder('—'),

                                        TextEntry::make('users.name')->label(__('projects::filament/resources/task.infolist.sections.project-information.entries.assignees'))
                                            ->icon('heroicon-o-users')
                                            ->listWithLineBreaks()
                                            ->placeholder('—'),

                                        TextEntry::make('deadline')->label(__('projects::filament/resources/task.infolist.sections.project-information.entries.deadline'))
                                            ->icon('heroicon-o-calendar')
                                            ->dateTime()
                                            ->placeholder('—'),
                                    ]),
                            ]),

                        Section::make(__('projects::filament/resources/task.infolist.sections.time-tracking.title'))
                            ->schema([
                                Grid::make(2)->schema([
                                        TextEntry::make('allocated_hours')->label(__('projects::filament/resources/task.infolist.sections.time-tracking.entries.allocated-time'))
                                            ->icon('heroicon-o-clock')
                                            ->suffix(' Hours')
                                            ->placeholder('—')
                                            ->formatStateUsing(function ($state): string {
                                                $hours = floor($state);
                                                $minutes = ($state - $hours) * 60;

                                                return $hours.':'.$minutes;
                                            })
                                            ->visible(fn (TimeSettings $timeSettings): bool => $timeSettings->enable_timesheets),

                                        TextEntry::make('total_hours_spent')->label(__('projects::filament/resources/task.infolist.sections.time-tracking.entries.time-spent'))
                                            ->icon('heroicon-o-clock')
                                            ->suffix(__('projects::filament/resources/task.infolist.sections.time-tracking.entries.time-spent-suffix'))
                                            ->formatStateUsing(function ($state): string {
                                                $hours = floor($state);
                                                $minutes = ($state - $hours) * 60;

                                                return $hours.':'.$minutes;
                                            })
                                            ->visible(fn (TimeSettings $timeSettings): bool => $timeSettings->enable_timesheets),

                                        TextEntry::make('remaining_hours')->label(__('projects::filament/resources/task.infolist.sections.time-tracking.entries.time-remaining'))
                                            ->icon('heroicon-o-clock')
                                            ->suffix(__('projects::filament/resources/task.infolist.sections.time-tracking.entries.time-remaining-suffix'))
                                            ->formatStateUsing(function ($state): string {
                                                $hours = floor($state);
                                                $minutes = ($state - $hours) * 60;

                                                return $hours.':'.$minutes;
                                            })
                                            ->color(fn ($state): string => $state < 0 ? 'danger' : 'success')
                                            ->visible(fn (TimeSettings $timeSettings): bool => $timeSettings->enable_timesheets),

                                        TextEntry::make('progress')->label(__('projects::filament/resources/task.infolist.sections.time-tracking.entries.progress'))
                                            ->icon('heroicon-o-chart-bar')
                                            ->suffix('%')
                                            ->color(
                                                fn ($record): string => $record->progress > 100
                                                    ? 'danger'
                                                    : ($record->progress < 100 ? 'warning' : 'success')
                                            )
                                            ->visible(fn (TimeSettings $timeSettings): bool => $timeSettings->enable_timesheets),
                                    ]),
                            ])
                            ->visible(fn (TimeSettings $timeSettings): bool => $timeSettings->enable_timesheets),

                        Section::make(__('projects::filament/resources/task.infolist.sections.additional-information.title'))
                            ->visible(($customInfolistEntries = self::getCustomInfolistEntries()) !== [])
                            ->schema($customInfolistEntries),
                    ])
                    ->columnSpan(['lg' => 2]),

                Group::make()->schema([
                        Section::make(__('projects::filament/resources/task.infolist.sections.record-information.title'))
                            ->schema([
                                TextEntry::make('created_at')->label(__('projects::filament/resources/task.infolist.sections.record-information.entries.created-at'))
                                    ->dateTime()
                                    ->icon('heroicon-m-calendar'),

                                TextEntry::make('creator.name')->label(__('projects::filament/resources/task.infolist.sections.record-information.entries.created-by'))
                                    ->icon('heroicon-m-user'),

                                TextEntry::make('updated_at')->label(__('projects::filament/resources/task.infolist.sections.record-information.entries.last-updated'))
                                    ->dateTime()
                                    ->icon('heroicon-m-calendar-days'),
                            ]),

                        Section::make(__('projects::filament/resources/task.infolist.sections.statistics.title'))
                            ->schema([
                                TextEntry::make('subtasks_count')->label(__('projects::filament/resources/task.infolist.sections.statistics.entries.sub-tasks'))
                                    ->state(fn (Task $record): int => $record->subTasks()->count())
                                    ->icon('heroicon-o-clipboard-document-list'),

                                TextEntry::make('timesheets_count')->label(__('projects::filament/resources/task.infolist.sections.statistics.entries.timesheet-entries'))
                                    ->state(fn (Task $record): int => $record->timesheets()->count())
                                    ->icon('heroicon-o-clock')
                                    ->visible(fn (TimeSettings $timeSettings): bool => $timeSettings->enable_timesheets),
                            ]),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            ViewTask::class,
            EditTask::class,
            ManageTimesheets::class,
            ManageSubTasks::class,
        ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationGroup::make('Timesheets', [
                TimesheetsRelationManager::class,
            ])->icon('heroicon-o-clock'),

            RelationGroup::make('Sub Tasks', [
                SubTasksRelationManager::class,
            ])->icon('heroicon-o-clipboard-document-list'),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTasks::route('/'),
            'create' => CreateTask::route('/create'),
            'edit' => EditTask::route('/{record}/edit'),
            'view' => ViewTask::route('/{record}'),
            'timesheets' => ManageTimesheets::route('/{record}/timesheets'),
            'sub-tasks' => ManageSubTasks::route('/{record}/sub-tasks'),
        ];
    }
}
