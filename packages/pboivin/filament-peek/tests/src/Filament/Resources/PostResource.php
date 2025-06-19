<?php

declare(strict_types=1);

namespace Pboivin\FilamentPeek\Tests\Filament\Resources;

use Filament\Resources\Resource;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Pboivin\FilamentPeek\Forms\Actions\InlinePreviewAction;
use Pboivin\FilamentPeek\Tests\Filament\Resources\PostResource\Pages\CreatePost;
use Pboivin\FilamentPeek\Tests\Filament\Resources\PostResource\Pages\EditPost;
use Pboivin\FilamentPeek\Tests\Filament\Resources\PostResource\Pages\ListPosts;
use Pboivin\FilamentPeek\Tests\Models\Post;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Actions::make([
                InlinePreviewAction::make()
                    ->label('Test_Builder_Preview')
                    ->builderPreview('content_blocks'),
            ])
                ->columnSpanFull()
                ->alignRight(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([])
            ->recordActions([])
            ->filters([])
            ->toolbarActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPosts::route('/'),
            'create' => CreatePost::route('/create'),
            'edit' => EditPost::route('/{record}/edit'),
        ];
    }
}
