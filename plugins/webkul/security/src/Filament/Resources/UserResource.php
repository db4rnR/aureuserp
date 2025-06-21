<?php

declare(strict_types=1);

namespace Webkul\Security\Filament\Resources;

use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Grid as InfolistGrid;
use Filament\Infolists\Components\Group as InfolistGroup;
use Filament\Infolists\Components\Section as InfolistSection;
use Filament\Forms\Form;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Webkul\Security\Enums\PermissionType;
use Webkul\Security\Filament\Resources\UserResource\Pages\CreateUser;
use Webkul\Security\Filament\Resources\UserResource\Pages\EditUser;
use Webkul\Security\Filament\Resources\UserResource\Pages\ListUsers;
use Webkul\Security\Filament\Resources\UserResource\Pages\ViewUsers;
use Webkul\Security\Models\User;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    protected static ?int $navigationSort = 4;

    public static function getNavigationLabel(): string
    {
        return __('security::filament/resources/user.navigation.title');
    }

    public static function getNavigationGroup(): string
    {
        return __('security::filament/resources/user.navigation.group');
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            __('security::filament/resources/user.global-search.name') => $record->name ?? '—',
            __('security::filament/resources/user.global-search.email') => $record->email ?? '—',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
                Group::make()->schema([
                        Group::make()->schema([
                                Section::make(__('security::filament/resources/user.form.sections.general-information.title'))
                                    ->schema([
                                        TextInput::make('name')->label(__('security::filament/resources/user.form.sections.general-information.fields.name'))
                                            ->required()
                                            ->maxLength(255)
                                            ->live(onBlur: true),
                                        TextInput::make('email')->label(__('security::filament/resources/user.form.sections.general-information.fields.email'))
                                            ->email()
                                            ->required()
                                            ->unique(ignoreRecord: true)
                                            ->maxLength(255),
                                        TextInput::make('password')->label(__('security::filament/resources/user.form.sections.general-information.fields.password'))
                                            ->password()
                                            ->required()
                                            ->hiddenOn('edit')
                                            ->maxLength(255)
                                            ->rule('min:8'),
                                        TextInput::make('password_confirmation')->label(__('security::filament/resources/user.form.sections.general-information.fields.password-confirmation'))
                                            ->password()
                                            ->hiddenOn('edit')
                                            ->rule('required', fn ($get): bool => (bool) $get('password'))
                                            ->same('password'),
                                    ])
                                    ->columns(2),

                                Section::make(__('security::filament/resources/user.form.sections.permissions.title'))
                                    ->schema([
                                        Select::make('roles')->label(__('security::filament/resources/user.form.sections.permissions.fields.roles'))
                                            ->relationship('roles', 'name')
                                            ->multiple()
                                            ->preload()
                                            ->searchable(),
                                        Select::make('resource_permission')->label(__('security::filament/resources/user.form.sections.permissions.fields.resource-permission'))
                                            ->options(PermissionType::options())
                                            ->required()
                                            ->preload()
                                            ->searchable(),
                                        Select::make('teams')->label(__('security::filament/resources/user.form.sections.permissions.fields.teams'))
                                            ->relationship('teams', 'name')
                                            ->multiple()
                                            ->preload()
                                            ->searchable(),
                                    ])
                                    ->columns(2),
                            ])
                            ->columnSpan(['lg' => 2]),
                        Group::make()->schema([
                                Section::make(__('security::filament/resources/user.form.sections.avatar.title'))
                                    ->schema([
                                        FileUpload::make('avatar')->hiddenLabel()
                                            ->imageResizeMode('cover')
                                            ->image()
                                            ->imageEditor()
                                            ->directory('users/avatars')
                                            ->visibility('private'),
                                    ])
                                    ->columns(1),
                                Section::make(__('security::filament/resources/user.form.sections.lang-and-status.title'))
                                    ->schema([
                                        Select::make('language')->label(__('security::filament/resources/user.form.sections.lang-and-status.fields.language'))
                                            ->options([
                                                'en' => __('English'),
                                            ])
                                            ->searchable(),
                                        Toggle::make('is_active')->label(__('security::filament/resources/user.form.sections.lang-and-status.fields.status'))
                                            ->default(true),
                                    ])
                                    ->columns(1),
                                Section::make(__('security::filament/resources/user.form.sections.multi-company.title'))
                                    ->schema([
                                        Select::make('allowed_companies')->label(__('security::filament/resources/user.form.sections.multi-company.allowed-companies'))
                                            ->relationship('allowedCompanies', 'name')
                                            ->multiple()
                                            ->preload()
                                            ->searchable(),
                                        Select::make('default_company_id')->label(__('security::filament/resources/user.form.sections.multi-company.default-company'))
                                            ->relationship('defaultCompany', 'name')
                                            ->required()
                                            ->searchable()
                                            ->createOptionForm(fn (Schema $form): Schema => CompanyResource::form($form))
                                            ->createOptionAction(function (Action $action): void {
                                                $action
                                                    ->fillForm(fn (array $arguments): array => [
                                                        'user_id' => Auth::id(),
                                                    ])
                                                    ->mutateDataUsing(function (array $data) {
                                                        $data['user_id'] = Auth::id();

                                                        return $data;
                                                    });
                                            })
                                            ->preload(),
                                    ]),
                            ])
                            ->columnSpan(['lg' => 1]),
                    ])
                    ->columns(3),
            ])
            ->columns(12);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('partner.avatar')->size(50)
                    ->label(__('security::filament/resources/user.table.columns.avatar')),
                TextColumn::make('name')->label(__('security::filament/resources/user.table.columns.name'))
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                TextColumn::make('email')->label(__('security::filament/resources/user.table.columns.email'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('teams.name')->label(__('security::filament/resources/user.table.columns.teams'))
                    ->badge(),
                TextColumn::make('roles.name')->sortable()
                    ->label(__('security::filament/resources/user.table.columns.role')),
                TextColumn::make('resource_permission')->label(__('security::filament/resources/user.table.columns.resource-permission'))
                    ->formatStateUsing(fn ($state) => PermissionType::options()[$state] ?? $state)
                    ->sortable(),
                TextColumn::make('defaultCompany.name')->label(__('security::filament/resources/user.table.columns.default-company'))
                    ->sortable(),
                TextColumn::make('allowedCompanies.name')->label(__('security::filament/resources/user.table.columns.allowed-company'))
                    ->badge(),
                TextColumn::make('created_at')->label(__('security::filament/resources/user.table.columns.created-at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')->label(__('security::filament/resources/user.table.columns.updated-at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('resource_permission')->label(__('security::filament/resources/user.table.filters.resource-permission'))
                    ->searchable()
                    ->options(PermissionType::options())
                    ->preload(),
                SelectFilter::make('default_company')->relationship('defaultCompany', 'name')
                    ->label(__('security::filament/resources/user.table.filters.default-company'))
                    ->searchable()
                    ->preload(),
                SelectFilter::make('allowed_companies')->relationship('allowedCompanies', 'name')
                    ->label(__('security::filament/resources/user.table.filters.allowed-companies'))
                    ->multiple()
                    ->searchable()
                    ->preload(),
                SelectFilter::make('teams')->relationship('teams', 'name')
                    ->label(__('security::filament/resources/user.table.filters.teams'))
                    ->options(fn (): array => Role::query()->pluck('name', 'id')->all())
                    ->multiple()
                    ->searchable()
                    ->preload(),
                SelectFilter::make('roles')->label(__('security::filament/resources/user.table.filters.roles'))
                    ->relationship('roles', 'name')
                    ->options(fn (): array => Role::query()->pluck('name', 'id')->all())
                    ->multiple()
                    ->searchable()
                    ->preload(),
            ])
            ->filtersFormColumns(2)
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()->hidden(fn ($record) => $record->trashed()),
                    EditAction::make()->hidden(fn ($record) => $record->trashed())
                        ->successNotification(
                            Notification::make()->success()
                                ->title(__('security::filament/resources/user.table.actions.edit.notification.title'))
                                ->body(__('security::filament/resources/user.table.actions.edit.notification.body')),
                        ),
                    DeleteAction::make()->successNotification(
                            Notification::make()->success()
                                ->title(__('security::filament/resources/user.table.actions.delete.notification.title'))
                                ->body(__('security::filament/resources/user.table.actions.delete.notification.body')),
                        ),
                    RestoreAction::make()->successNotification(
                            Notification::make()->success()
                                ->title(__('security::filament/resources/user.table.actions.restore.notification.title'))
                                ->body(__('security::filament/resources/user.table.actions.restore.notification.body')),
                        ),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->successNotification(
                            Notification::make()->success()
                                ->title(__('security::filament/resources/user.table.bulk-actions.delete.notification.title'))
                                ->body(__('security::filament/resources/user.table.bulk-actions.delete.notification.body')),
                        ),
                    ForceDeleteBulkAction::make()->successNotification(
                            Notification::make()->success()
                                ->title(__('security::filament/resources/user.table.bulk-actions.force-delete.notification.title'))
                                ->body(__('security::filament/resources/user.table.bulk-actions.force-delete.notification.body')),
                        ),
                    RestoreBulkAction::make()->successNotification(
                            Notification::make()->success()
                                ->title(__('security::filament/resources/user.table.bulk-actions.restore.notification.title'))
                                ->body(__('security::filament/resources/user.table.bulk-actions.restore.notification.body')),
                        ),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->modifyQueryUsing(function ($query): void {
                $query->with('roles', 'teams', 'defaultCompany', 'allowedCompanies');
            })
            ->emptyStateActions([
                CreateAction::make()->icon('heroicon-o-plus-circle')
                    ->successNotification(
                        Notification::make()->success()
                            ->title(__('security::filament/resources/user.table.empty-state-actions.create.notification.title'))
                            ->body(__('security::filament/resources/user.table.empty-state-actions.create.notification.body')),
                    ),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $schema
            ->schema([
                InfolistGrid::make(['default' => 3])->schema([
                        InfolistGroup::make()->schema([
                                InfolistSection::make(__('security::filament/resources/user.infolist.sections.general-information.title'))
                                    ->schema([
                                        TextEntry::make('name')->icon('heroicon-o-user')
                                            ->placeholder('—')
                                            ->extraAttributes([
                                                'style' => 'word-break: break-all;',
                                            ])
                                            ->label(__('security::filament/resources/user.infolist.sections.general-information.entries.name')),
                                        TextEntry::make('email')->icon('heroicon-o-envelope')
                                            ->placeholder('—')
                                            ->label(__('security::filament/resources/user.infolist.sections.general-information.entries.email')),
                                        TextEntry::make('language')->icon('heroicon-o-language')
                                            ->placeholder('—')
                                            ->label(__('security::filament/resources/user.infolist.sections.lang-and-status.entries.language')),
                                    ])
                                    ->columns(2),

                                InfolistSection::make(__('security::filament/resources/user.infolist.sections.permissions.title'))
                                    ->schema([
                                        TextEntry::make('roles.name')->icon('heroicon-o-key')
                                            ->placeholder('—')
                                            ->label(__('security::filament/resources/user.infolist.sections.permissions.entries.roles'))
                                            ->listWithLineBreaks()
                                            ->formatStateUsing(fn ($state): string => ucfirst((string) $state))
                                            ->bulleted(),
                                        TextEntry::make('teams.name')->icon('heroicon-o-user-group')
                                            ->placeholder('—')
                                            ->label(__('security::filament/resources/user.infolist.sections.permissions.entries.teams'))
                                            ->listWithLineBreaks()
                                            ->bulleted(),
                                        TextEntry::make('resource_permission')->icon(fn ($record): string => [
                                                PermissionType::GLOBAL->value => 'heroicon-o-globe-alt',
                                                PermissionType::INDIVIDUAL->value => 'heroicon-o-user',
                                                PermissionType::GROUP->value => 'heroicon-o-user-group',
                                            ][$record->resource_permission])
                                            ->formatStateUsing(fn ($state) => PermissionType::options()[$state] ?? $state)
                                            ->placeholder('-')
                                            ->label(__('security::filament/resources/user.infolist.sections.permissions.entries.resource-permission')),
                                    ])
                                    ->columns(2),
                            ])
                            ->columnSpan(2),

                        InfolistGroup::make([
                            InfolistSection::make(__('security::filament/resources/user.infolist.sections.avatar.title'))
                                ->schema([
                                    ImageEntry::make('partner.avatar')->hiddenLabel()
                                        ->circular()
                                        ->placeholder('—'),
                                ]),

                            InfolistSection::make(__('security::filament/resources/user.infolist.sections.multi-company.title'))
                                ->schema([
                                    TextEntry::make('allowedCompanies.name')->icon('heroicon-o-building-office')
                                        ->placeholder('—')
                                        ->label(__('security::filament/resources/user.infolist.sections.multi-company.allowed-companies'))
                                        ->listWithLineBreaks()
                                        ->bulleted(),
                                    TextEntry::make('defaultCompany.name')->icon('heroicon-o-building-office-2')
                                        ->placeholder('—')
                                        ->label(__('security::filament/resources/user.infolist.sections.multi-company.default-company')),
                                ]),

                            InfolistSection::make(__('security::filament/resources/user.infolist.sections.lang-and-status.title'))
                                ->schema([
                                    IconEntry::make('is_active')->label(__('security::filament/resources/user.infolist.sections.lang-and-status.entries.status'))
                                        ->boolean(),
                                ]),
                        ])->columnSpan(1),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
            'view' => ViewUsers::route('/{record}'),
        ];
    }
}
