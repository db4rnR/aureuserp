<?php

declare(strict_types=1);

namespace Awcodes\Curator\Resources;

use Awcodes\Curator\Components\Forms\CuratorEditor;
use Awcodes\Curator\Components\Forms\Uploader;
use Awcodes\Curator\Components\Tables\CuratorColumn;
use Awcodes\Curator\CuratorPlugin;
use Awcodes\Curator\Resources\MediaResource\CreateMedia;
use Awcodes\Curator\Resources\MediaResource\EditMedia;
use Awcodes\Curator\Resources\MediaResource\ListMedia;
use Exception;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\Layout\View;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

use function Awcodes\Curator\is_media_resizable;

final class MediaResource extends Resource
{
    public static function getModel(): string
    {
        return config('curator.model');
    }

    public static function isScopedToTenant(): bool
    {
        return config('curator.is_tenant_aware') ?? self::$isScopedToTenant;
    }

    public static function getTenantOwnershipRelationshipName(): string
    {
        return config('curator.tenant_ownership_relationship_name') ?? Filament::getTenantOwnershipRelationshipName();
    }

    public static function getModelLabel(): string
    {
        return CuratorPlugin::get()->getLabel();
    }

    public static function getPluralModelLabel(): string
    {
        return CuratorPlugin::get()->getPluralLabel();
    }

    public static function getNavigationLabel(): string
    {
        return CuratorPlugin::get()->getNavigationLabel() ?? Str::title(self::getPluralModelLabel()) ?? Str::title(self::getModelLabel());
    }

    public static function getNavigationIcon(): string
    {
        return CuratorPlugin::get()->getNavigationIcon();
    }

    public static function getNavigationSort(): ?int
    {
        return CuratorPlugin::get()->getNavigationSort();
    }

    public static function getNavigationGroup(): ?string
    {
        return CuratorPlugin::get()->getNavigationGroup();
    }

    public static function getCluster(): ?string
    {
        return config('curator.resources.cluster');
    }

    public static function getNavigationBadge(): ?string
    {
        return CuratorPlugin::get()->getNavigationCountBadge()
            ? number_format(self::getNavigationBadgeCount())
            : null;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return CuratorPlugin::get()->shouldRegisterNavigation();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()
                    ->schema([
                        Section::make(trans('curator::forms.sections.file'))
                            ->hiddenOn('edit')
                            ->schema([
                                self::getUploaderField()
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function (Set $set, Uploader $component, $state): void {
                                        $name = $component->getSuggestedFileName($state);
                                        $set('name', $name);
                                    })
                                    ->getUploadedFileNameForStorageUsing(function (Get $get, Uploader $component, $file) {
                                        $name = $get('name');

                                        return ! empty($name) ? Str::slug($name) : $component->getSuggestedFileName($file);
                                    }),
                            ]),
                        Tabs::make('image')
                            ->hiddenOn('create')
                            ->tabs([
                                Tab::make(trans('curator::forms.sections.preview'))
                                    ->schema([
                                        ViewField::make('preview')
                                            ->view('curator::components.forms.preview')
                                            ->hiddenLabel()
                                            ->dehydrated(false)
                                            ->afterStateHydrated(function ($component, $state, $record): void {
                                                $component->state($record);
                                            }),
                                    ]),
                                Tab::make(trans('curator::forms.sections.curation'))
                                    ->visible(fn ($record) => is_media_resizable($record->type) && config('curator.tabs.display_curation'))
                                    ->schema([
                                        Repeater::make('curations')
                                            ->label(trans('curator::forms.sections.curation'))
                                            ->hiddenLabel()
                                            ->reorderable(false)
                                            ->itemLabel(fn ($state): ?string => $state['curation']['key'] ?? null)
                                            ->collapsible()
                                            ->schema([
                                                CuratorEditor::make('curation')
                                                    ->hiddenLabel()
                                                    ->buttonLabel(trans('curator::forms.curations.button_label'))
                                                    ->required()
                                                    ->lazy(),
                                            ]),
                                    ]),
                                Tab::make(trans('curator::forms.sections.upload_new'))
                                    ->visible(config('curator.tabs.display_upload_new'))
                                    ->schema([
                                        self::getUploaderField()
                                            ->helperText(trans('curator::forms.sections.upload_new_helper')),
                                    ]),
                            ]),
                        Section::make(trans('curator::forms.sections.details'))
                            ->schema([
                                ViewField::make('details')
                                    ->view('curator::components.forms.details')
                                    ->hiddenLabel()
                                    ->dehydrated(false)
                                    ->columnSpan('full')
                                    ->afterStateHydrated(function ($component, $state, $record): void {
                                        $component->state($record);
                                    }),
                            ]),
                        Section::make(trans('curator::forms.sections.exif'))
                            ->collapsed()
                            ->visible(fn ($record) => $record && $record->exif)
                            ->schema([
                                KeyValue::make('exif')
                                    ->hiddenLabel()
                                    ->dehydrated(false)
                                    ->addable(false)
                                    ->deletable(false)
                                    ->editableKeys(false)
                                    ->columnSpan('full'),
                            ]),
                    ])
                    ->columnSpan([
                        'md' => 'full',
                        'lg' => 2,
                    ]),
                Group::make()
                    ->schema([
                        Section::make(trans('curator::forms.sections.meta'))
                            ->schema(
                                self::getAdditionalInformationFormSchema()
                            ),
                    ])->columnSpan([
                        'md' => 'full',
                        'lg' => 1,
                    ]),
            ])->columns([
                'lg' => 3,
            ]);
    }

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        $livewire = $table->getLivewire();

        return $table
            ->columns(
                $livewire->layoutView === 'grid'
                    ? self::getDefaultGridTableColumns()
                    : self::getDefaultTableColumns(),
            )
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ])
            ->defaultSort('created_at', 'desc')
            ->contentGrid(function () use ($livewire) {
                if ($livewire->layoutView === 'grid') {
                    return [
                        'md' => 2,
                        'lg' => 3,
                        'xl' => 4,
                    ];
                }

                return null;
            })
            ->defaultPaginationPageOption(12)
            ->paginationPageOptions([6, 12, 24, 48, 'all'])
            ->recordUrl(false);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMedia::route('/'),
            'create' => CreateMedia::route('/create'),
            'edit' => EditMedia::route('/{record}/edit'),
        ];
    }

    public static function getDefaultTableColumns(): array
    {
        return [
            CuratorColumn::make('url')
                ->label(trans('curator::tables.columns.url'))
                ->size(40),
            TextColumn::make('name')
                ->label(trans('curator::tables.columns.name'))
                ->searchable(['name', 'title'])
                ->sortable(),
            TextColumn::make('ext')
                ->label(trans('curator::tables.columns.ext'))
                ->sortable(),
            IconColumn::make('disk')
                ->label(trans('curator::tables.columns.disk'))
                ->icons([
                    'heroicon-o-server',
                    'heroicon-o-cloud' => fn ($state): bool => in_array($state, config('curator.cloud_disks'), true),
                ])
                ->colors([
                    'gray',
                    'success' => fn ($state): bool => in_array($state, config('curator.cloud_disks'), true),
                ]),
            TextColumn::make('directory')
                ->label(trans('curator::tables.columns.directory'))
                ->sortable(),
            TextColumn::make('created_at')
                ->label(trans('curator::tables.columns.created_at'))
                ->date('Y-m-d')
                ->sortable(),
        ];
    }

    public static function getDefaultGridTableColumns(): array
    {
        return [
            View::make('curator::components.tables.grid-column'),
            TextColumn::make('name')
                ->label(trans('curator::tables.columns.name'))
                ->extraAttributes(['class' => 'hidden'])
                ->searchable(['name', 'title'])
                ->sortable(),
            TextColumn::make('ext')
                ->label(trans('curator::tables.columns.ext'))
                ->extraAttributes(['class' => 'hidden'])
                ->sortable(),
            TextColumn::make('directory')
                ->label(trans('curator::tables.columns.directory'))
                ->extraAttributes(['class' => 'hidden'])
                ->sortable(),
            TextColumn::make('created_at')
                ->label(trans('curator::tables.columns.created_at'))
                ->extraAttributes(['class' => 'hidden'])
                ->sortable(),
        ];
    }

    public static function getAdditionalInformationFormSchema(): array
    {
        return [
            TextInput::make('name')
                ->label(trans('curator::forms.fields.name'))
                ->required(fn ($operation): bool => $operation === 'edit')
                ->dehydrateStateUsing(function ($component, $state) {
                    $slugged = Str::slug($state);
                    $component->state($slugged);

                    return $slugged;
                }),
            TextInput::make('alt')
                ->label(trans('curator::forms.fields.alt'))
                ->hint(fn (): HtmlString => new HtmlString('<a href="https://www.w3.org/WAI/tutorials/images/decision-tree" class="filament-link text-primary-500" target="_blank">'.trans('curator::forms.fields.alt_hint').'</a>')),
            TextInput::make('title')
                ->label(trans('curator::forms.fields.title')),
            Textarea::make('caption')
                ->label(trans('curator::forms.fields.caption'))
                ->rows(2),
            Textarea::make('description')
                ->label(trans('curator::forms.fields.description'))
                ->rows(2),
        ];
    }

    public static function getUploaderField(): Uploader
    {
        return Uploader::make('file')
            ->acceptedFileTypes(config('curator.accepted_file_types'))
            ->directory(config('curator.directory'))
            ->disk(config('curator.disk'))
            ->hiddenLabel()
            ->minSize(config('curator.min_size'))
            ->maxFiles(1)
            ->maxSize(config('curator.max_size'))
            ->panelAspectRatio('24:9')
            ->pathGenerator(config('curator.path_generator'))
            ->preserveFilenames(config('curator.should_preserve_filenames'))
            ->visibility(config('curator.visibility'))
            ->storeFileNamesIn('originalFilename');
    }

    protected static function getNavigationBadgeCount(): int
    {
        if (Filament::hasTenancy() && Config::get('curator.is_tenant_aware')) {
            return self::getEloquentQuery()
                ->where(Config::get('curator.tenant_ownership_relationship_name').'_id', Filament::getTenant()->id)
                ->count();
        }

        return self::getModel()::count();
    }
}
