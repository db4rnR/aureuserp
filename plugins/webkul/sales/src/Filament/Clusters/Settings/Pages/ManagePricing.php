<?php

declare(strict_types=1);

namespace Webkul\Sale\Filament\Clusters\Settings\Pages;

use BackedEnum;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms\Components\Toggle;
use Filament\Pages\SettingsPage;
use Filament\Forms\Form;
use UnitEnum;
use Webkul\Sale\Settings\PriceSettings;
use Webkul\Support\Filament\Clusters\Settings;

class ManagePricing extends SettingsPage
{
    use HasPageShield;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $slug = 'sale/manage-pricing';

    protected static string|UnitEnum|null $navigationGroup = 'Sales';

    protected static ?int $navigationSort = 2;

    protected static string $settings = PriceSettings::class;

    protected static ?string $cluster = Settings::class;

    public static function getNavigationLabel(): string
    {
        return __('sales::filament/clusters/settings/pages/manage-pricing.navigation.title');
    }

    public function getBreadcrumbs(): array
    {
        return [
            __('sales::filament/clusters/settings/pages/manage-pricing.breadcrumb'),
        ];
    }

    public function getTitle(): string
    {
        return __('sales::filament/clusters/settings/pages/manage-pricing.title');
    }

    public function form(Form $form): Form
    {
        return $form
            ->components([
                Toggle::make('enable_discount')->label(__('sales::filament/clusters/settings/pages/manage-pricing.form.fields.discount'))
                    ->helperText(__('sales::filament/clusters/settings/pages/manage-pricing.form.fields.discount-help')),
                Toggle::make('enable_margin')->label(__('sales::filament/clusters/settings/pages/manage-pricing.form.fields.margins'))
                    ->helperText(__('sales::filament/clusters/settings/pages/manage-pricing.form.fields.margins-help')),
            ]);
    }
}
