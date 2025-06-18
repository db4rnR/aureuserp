<?php

declare(strict_types=1);

namespace Webkul\Website\Filament\Customer\Pages;

use Filament\Pages\Page;
use Filament\Panel;
use Illuminate\Contracts\Support\Htmlable;
use Webkul\Website\Models\Page as PageModel;

final class Homepage extends Page
{
    protected static ?int $navigationSort = -2;

    /**
     * @var view-string
     */
    protected string $view = 'website::filament.customer.pages.homepage';

    private static string $routePath = '/';

    public static function getNavigationLabel(): string
    {
        return 'Home';
    }

    public static function getRoutePath(Panel $panel): string
    {
        return self::$routePath;
    }

    public function getTitle(): string|Htmlable
    {
        return 'Homepage';
    }

    public function getContent(): string|Htmlable
    {
        $homePage = PageModel::where('slug', 'home')->first();

        return $homePage?->content ?? '';
    }
}
