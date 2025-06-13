<?php

namespace Webkul\Blog\Filament\Customer\Resources;

use Filament\Resources\Resource;
use Webkul\Blog\Filament\Customer\Resources\CategoryResource\Pages;
use Webkul\Blog\Filament\Customer\Resources\PostResource\Pages\ViewPost;
use Webkul\Blog\Models\Category;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $slug = 'blog';

    protected static ?string $recordRouteKeyName = 'slug';

    protected static bool $shouldSkipAuthorization = true;

    public static function getNavigationLabel(): string
    {
        return __('blogs::filament/customer/resources/category.navigation.title');
    }

    public static function getPages(): array
    {
        return [
            'index'      => Pages\ListCategories::route('/'),
            'view'       => Pages\ViewCategory::route('/{record}'),
            'posts.view' => ViewPost::route('/{parent}/{record}'),
        ];
    }
}
