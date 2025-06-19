<?php

declare(strict_types=1);

namespace Pboivin\FilamentPeek\Tests\Filament\Resources;

use Filament\Resources\Resource;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Pboivin\FilamentPeek\Forms\Actions\InlinePreviewAction;
use Pboivin\FilamentPeek\Tables\Actions\ListPreviewAction;
use Pboivin\FilamentPeek\Tests\Filament\Resources\PageResource\Pages\CreatePage;
use Pboivin\FilamentPeek\Tests\Filament\Resources\PageResource\Pages\EditPage;
use Pboivin\FilamentPeek\Tests\Filament\Resources\PageResource\Pages\ListPages;
use Pboivin\FilamentPeek\Tests\Models\Page;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Actions::make([
                InlinePreviewAction::make()
                    ->label('Preview Changes')
                    ->previewModalData(fn () => ['initial_data' => 'InlinePreviewAction']),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title'),
            ])
            ->recordActions([
                ListPreviewAction::make()
                    ->previewModalData(fn () => ['initial_data' => 'ListPreviewAction']),
            ])
            ->filters([])
            ->toolbarActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPages::route('/'),
            'create' => CreatePage::route('/create'),
            'edit' => EditPage::route('/{record}/edit'),
        ];
    }
}
