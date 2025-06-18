<?php

declare(strict_types=1);

namespace FilamentTiptapEditor\Tests\Resources;

use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use FilamentTiptapEditor\Enums\TiptapOutput;
use FilamentTiptapEditor\Tests\Models\Page;
use FilamentTiptapEditor\Tests\Resources\PageResource\Pages\CreatePage;
use FilamentTiptapEditor\Tests\Resources\PageResource\Pages\EditPage;
use FilamentTiptapEditor\Tests\Resources\PageResource\Pages\ListPages;
use FilamentTiptapEditor\TiptapEditor;

final class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title'),
                TiptapEditor::make('html_content')
                    ->output(TiptapOutput::Html),
                TiptapEditor::make('json_content')
                    ->output(TiptapOutput::Json),
                TiptapEditor::make('text_content')
                    ->output(TiptapOutput::Text),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                CreateAction::make(),
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
            'index' => ListPages::route('/'),
            'create' => CreatePage::route('/create'),
            'edit' => EditPage::route('/{record}/edit'),
        ];
    }
}
