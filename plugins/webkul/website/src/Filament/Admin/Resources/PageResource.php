<?php

namespace Webkul\Website\Filament\Admin\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Webkul\Website\Filament\Admin\Resources\PageResource\Pages;
use Webkul\Website\Models\Page as PageModel;

class PageResource extends Resource
{
    protected static ?string $model = PageModel::class;

    protected static ?string $slug = 'website/pages';

    protected static ?string $navigationIcon = 'heroicon-o-window';

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    protected static ?string $recordTitleAttribute = 'title';

    public static function getNavigationLabel(): string
    {
        return __('website::filament/admin/resources/page.navigation.title');
    }

    public static function getNavigationGroup(): string
    {
        return __('website::filament/admin/resources/page.navigation.group');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make(__('website::filament/admin/resources/page.form.sections.general.title'))
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->label(__('website::filament/admin/resources/page.form.sections.general.fields.title'))
                                    ->required()
                                    ->live(onBlur: true)
                                    ->placeholder(__('website::filament/admin/resources/page.form.sections.general.fields.title-placeholder'))
                                    ->extraInputAttributes(['style' => 'font-size: 1.5rem;height: 3rem;'])
                                    ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),
                                Forms\Components\TextInput::make('slug')
                                    ->disabled()
                                    ->dehydrated()
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(PageModel::class, 'slug', ignoreRecord: true),
                                Forms\Components\RichEditor::make('content')
                                    ->label(__('website::filament/admin/resources/page.form.sections.general.fields.content'))
                                    ->required(),
                            ]),

                        Forms\Components\Section::make(__('website::filament/admin/resources/page.form.sections.seo.title'))
                            ->schema([
                                Forms\Components\TextInput::make('meta_title')
                                    ->label(__('website::filament/admin/resources/page.form.sections.seo.fields.meta-title')),
                                Forms\Components\TextInput::make('meta_keywords')
                                    ->label(__('website::filament/admin/resources/page.form.sections.seo.fields.meta-keywords')),
                                Forms\Components\Textarea::make('meta_description')
                                    ->label(__('website::filament/admin/resources/page.form.sections.seo.fields.meta-description')),
                            ]),
                    ])
                    ->columnSpan(['lg' => 2]),
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make(__('website::filament/admin/resources/page.form.sections.settings.title'))
                            ->schema([
                                Forms\Components\Toggle::make('is_header_visible')
                                    ->label(__('website::filament/admin/resources/page.form.sections.settings.fields.is-header-visible'))
                                    ->inline(false),
                                Forms\Components\Toggle::make('is_footer_visible')
                                    ->label(__('website::filament/admin/resources/page.form.sections.settings.fields.is-footer-visible'))
                                    ->inline(false),
                            ]),
                    ]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('website::filament/admin/resources/page.table.columns.title'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->label(__('website::filament/admin/resources/page.table.columns.slug'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('creator.name')
                    ->label(__('website::filament/admin/resources/page.table.columns.creator'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('is_published')
                    ->label(__('website::filament/admin/resources/page.table.columns.is-published'))
                    ->boolean()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_header_visible')
                    ->label(__('website::filament/admin/resources/page.table.columns.is-header-visible'))
                    ->boolean()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('is_footer_visible')
                    ->label(__('website::filament/admin/resources/page.table.columns.is-footer-visible'))
                    ->boolean()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('website::filament/admin/resources/page.table.columns.updated-at'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('website::filament/admin/resources/page.table.columns.created-at'))
                    ->sortable(),
            ])
            ->groups([
                Tables\Grouping\Group::make('created_at')
                    ->label(__('website::filament/admin/resources/page.table.groups.created-at'))
                    ->date(),
            ])
            ->filters([
                Tables\Filters\Filter::make('is_published')
                    ->label(__('website::filament/admin/resources/page.table.filters.is-published')),
                Tables\Filters\SelectFilter::make('creator_id')
                    ->label(__('website::filament/admin/resources/page.table.filters.creator'))
                    ->relationship('creator', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->hidden(fn ($record) => $record->trashed()),
                    Tables\Actions\EditAction::make()
                        ->hidden(fn ($record) => $record->trashed()),
                    Tables\Actions\RestoreAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('website::filament/admin/resources/page.table.actions.restore.notification.title'))
                                ->body(__('website::filament/admin/resources/page.table.actions.restore.notification.body')),
                        ),
                    Tables\Actions\DeleteAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('website::filament/admin/resources/page.table.actions.delete.notification.title'))
                                ->body(__('website::filament/admin/resources/page.table.actions.delete.notification.body')),
                        ),
                    Tables\Actions\ForceDeleteAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('website::filament/admin/resources/page.table.actions.force-delete.notification.title'))
                                ->body(__('website::filament/admin/resources/page.table.actions.force-delete.notification.body')),
                        ),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\RestoreBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('website::filament/admin/resources/page.table.bulk-actions.restore.notification.title'))
                                ->body(__('website::filament/admin/resources/page.table.bulk-actions.restore.notification.body')),
                        ),
                    Tables\Actions\DeleteBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('website::filament/admin/resources/page.table.bulk-actions.delete.notification.title'))
                                ->body(__('website::filament/admin/resources/page.table.bulk-actions.delete.notification.body')),
                        ),
                    Tables\Actions\ForceDeleteBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('website::filament/admin/resources/page.table.bulk-actions.force-delete.notification.title'))
                                ->body(__('website::filament/admin/resources/page.table.bulk-actions.force-delete.notification.body')),
                        ),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Group::make()
                    ->schema([
                        Infolists\Components\Section::make(__('website::filament/admin/resources/page.form.sections.general.title'))
                            ->schema([
                                Infolists\Components\TextEntry::make('title')
                                    ->label(__('website::filament/admin/resources/page.form.sections.general.fields.title'))
                                    ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                                    ->weight(\Filament\Support\Enums\FontWeight::Bold),

                                Infolists\Components\TextEntry::make('content')
                                    ->label(__('website::filament/admin/resources/page.form.sections.general.fields.content'))
                                    ->markdown(),
                            ]),

                        Infolists\Components\Section::make(__('website::filament/admin/resources/page.form.sections.seo.title'))
                            ->schema([
                                Infolists\Components\TextEntry::make('meta_title')
                                    ->label(__('website::filament/admin/resources/page.form.sections.seo.fields.meta-title'))
                                    ->icon('heroicon-o-document-text')
                                    ->placeholder('—'),

                                Infolists\Components\TextEntry::make('meta_keywords')
                                    ->label(__('website::filament/admin/resources/page.form.sections.seo.fields.meta-keywords'))
                                    ->icon('heroicon-o-hashtag')
                                    ->placeholder('—'),

                                Infolists\Components\TextEntry::make('meta_description')
                                    ->label(__('website::filament/admin/resources/page.form.sections.seo.fields.meta-description'))
                                    ->markdown()
                                    ->placeholder('—'),
                            ]),
                    ])
                    ->columnSpan(['lg' => 2]),

                Infolists\Components\Group::make()
                    ->schema([
                        Infolists\Components\Section::make(__('website::filament/admin/resources/page.infolist.sections.record-information.title'))
                            ->schema([
                                Infolists\Components\TextEntry::make('creator.name')
                                    ->label(__('website::filament/admin/resources/page.infolist.sections.record-information.entries.created-by'))
                                    ->icon('heroicon-m-user'),

                                Infolists\Components\TextEntry::make('published_at')
                                    ->label(__('website::filament/admin/resources/page.infolist.sections.record-information.entries.published-at'))
                                    ->dateTime()
                                    ->icon('heroicon-m-calendar-days')
                                    ->placeholder('—'),

                                Infolists\Components\TextEntry::make('created_at')
                                    ->label(__('website::filament/admin/resources/page.infolist.sections.record-information.entries.created-at'))
                                    ->dateTime()
                                    ->icon('heroicon-m-calendar'),

                                Infolists\Components\TextEntry::make('updated_at')
                                    ->label(__('website::filament/admin/resources/page.infolist.sections.record-information.entries.last-updated'))
                                    ->dateTime()
                                    ->icon('heroicon-m-calendar-days'),

                                Infolists\Components\IconEntry::make('is_published')
                                    ->label(__('website::filament/admin/resources/page.table.columns.is-published'))
                                    ->boolean(),

                            ]),

                        Infolists\Components\Section::make(__('website::filament/admin/resources/page.infolist.sections.settings.title'))
                            ->schema([
                                Infolists\Components\IconEntry::make('is_header_visible')
                                    ->label(__('website::filament/admin/resources/page.infolist.sections.settings.entries.is-header-visible'))
                                    ->boolean(),

                                Infolists\Components\IconEntry::make('is_footer_visible')
                                    ->label(__('website::filament/admin/resources/page.infolist.sections.settings.entries.is-footer-visible'))
                                    ->boolean(),
                            ]),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\ViewPage::class,
            Pages\EditPage::class,
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'view'   => Pages\ViewPage::route('/{record}'),
            'edit'   => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
