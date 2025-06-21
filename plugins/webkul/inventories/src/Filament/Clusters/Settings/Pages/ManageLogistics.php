<?php

declare(strict_types=1);

namespace Webkul\Inventory\Filament\Clusters\Settings\Pages;

use BackedEnum;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms\Components\Toggle;
use Filament\Pages\SettingsPage;
use Filament\Forms\Form;
use UnitEnum;
use Webkul\Inventory\Enums;
use Webkul\Inventory\Models\OperationType;
use Webkul\Inventory\Settings\LogisticSettings;
use Webkul\Support\Filament\Clusters\Settings;

class ManageLogistics extends SettingsPage
{
    use HasPageShield;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-truck';

    protected static ?string $slug = 'inventory/manage-logistics';

    protected static string|UnitEnum|null $navigationGroup = 'Inventory';

    protected static ?int $navigationSort = 5;

    protected static string $settings = LogisticSettings::class;

    protected static ?string $cluster = Settings::class;

    public static function getNavigationLabel(): string
    {
        return __('inventories::filament/clusters/settings/pages/manage-logistics.title');
    }

    public function getBreadcrumbs(): array
    {
        return [
            __('inventories::filament/clusters/settings/pages/manage-logistics.title'),
        ];
    }

    public function getTitle(): string
    {
        return __('inventories::filament/clusters/settings/pages/manage-logistics.title');
    }

    public function form(Form $form): Form
    {
        return $form
            ->components([
                Toggle::make('enable_dropshipping')->label(__('inventories::filament/clusters/settings/pages/manage-logistics.form.enable-dropshipping'))
                    ->helperText(__('inventories::filament/clusters/settings/pages/manage-logistics.form.enable-dropshipping-helper-text')),
            ]);
    }

    private function afterSave(): void
    {
        OperationType::withTrashed()->where('type', Enums\OperationType::DROPSHIP)->update(['deleted_at' => $this->data['enable_dropshipping'] ? null : now()]);
    }
}
