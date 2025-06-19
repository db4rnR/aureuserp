<?php

declare(strict_types=1);

namespace Webkul\Invoice\Filament\Clusters\Settings\Pages;

use BackedEnum;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms\Components\Toggle;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Schema;
use UnitEnum;
use Webkul\Invoice\Settings\ProductSettings;
use Webkul\Support\Filament\Clusters\Settings;

class Products extends SettingsPage
{
    use HasPageShield;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cube';

    protected static string|UnitEnum|null $navigationGroup = 'Invoices';

    protected static ?int $navigationSort = 1;

    protected static string $settings = ProductSettings::class;

    protected static ?string $cluster = Settings::class;

    public static function getNavigationLabel(): string
    {
        return __('Manage Products');
    }

    public function getBreadcrumbs(): array
    {
        return [
            __('Manage Products'),
        ];
    }

    public function getTitle(): string
    {
        return __('Manage Products');
    }

    public function form(Form $form): Form
    {
        return $form
            ->components([
                Toggle::make('enable_uom')->label(__('Unit of Measure'))
                    ->helperText(__('Sell and purchase products in different units of measure')),
            ]);
    }
}
