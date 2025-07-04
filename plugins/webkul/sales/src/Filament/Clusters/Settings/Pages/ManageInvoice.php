<?php

declare(strict_types=1);

namespace Webkul\Sale\Filament\Clusters\Settings\Pages;

use BackedEnum;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms\Components\Radio;
use Filament\Pages\SettingsPage;
use Filament\Forms\Form;
use UnitEnum;
use Webkul\Invoice\Enums\InvoicePolicy;
use Webkul\Sale\Settings\InvoiceSettings;
use Webkul\Support\Filament\Clusters\Settings;

class ManageInvoice extends SettingsPage
{
    use HasPageShield;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $slug = 'sale/manage-invoicing';

    protected static string|UnitEnum|null $navigationGroup = 'Sales';

    protected static ?int $navigationSort = 2;

    protected static string $settings = InvoiceSettings::class;

    protected static ?string $cluster = Settings::class;

    public static function getNavigationLabel(): string
    {
        return __('sales::filament/clusters/settings/pages/manage-invoice.navigation.title');
    }

    public function getBreadcrumbs(): array
    {
        return [
            __('sales::filament/clusters/settings/pages/manage-invoice.breadcrumb'),
        ];
    }

    public function getTitle(): string
    {
        return __('sales::filament/clusters/settings/pages/manage-invoice.title');
    }

    public function form(Form $form): Form
    {
        return $form
            ->components([
                Radio::make('invoice_policy')->options(InvoicePolicy::class)
                    ->default('delivery')
                    ->label(__('sales::filament/clusters/settings/pages/manage-invoice.form.invoice-policy.label'))
                    ->helperText(__('sales::filament/clusters/settings/pages/manage-invoice.form.invoice-policy.label-help')),
            ]);
    }
}
