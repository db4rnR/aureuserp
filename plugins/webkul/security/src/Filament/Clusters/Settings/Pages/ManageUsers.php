<?php

declare(strict_types=1);

namespace Webkul\Security\Filament\Clusters\Settings\Pages;

use BackedEnum;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Schema;
use Spatie\Permission\Models\Role;
use Webkul\Security\Settings\UserSettings;
use Webkul\Support\Filament\Clusters\Settings;
use Webkul\Support\Models\Company;

class ManageUsers extends SettingsPage
{
    use HasPageShield;

    protected static ?string $cluster = Settings::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    protected static string $settings = UserSettings::class;

    public static function getNavigationGroup(): string
    {
        return __('security::filament/clusters/manage-users.group');
    }

    public static function getNavigationLabel(): string
    {
        return __('security::filament/clusters/manage-users.navigation.label');
    }

    public function getBreadcrumbs(): array
    {
        return [
            __('security::filament/clusters/manage-users.breadcrumb'),
        ];
    }

    public function getTitle(): string
    {
        return __('security::filament/clusters/manage-users.title');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Toggle::make('enable_user_invitation')->label(__('security::filament/clusters/manage-users.form.enable-user-invitation.label'))
                    ->helperText(__('security::filament/clusters/manage-users.form.enable-user-invitation.helper-text'))
                    ->required(),
                Toggle::make('enable_reset_password')->label(__('security::filament/clusters/manage-users.form.enable-reset-password.label'))
                    ->helperText(__('security::filament/clusters/manage-users.form.enable-reset-password.helper-text'))
                    ->required(),
                Select::make('default_role_id')->label(__('security::filament/clusters/manage-users.form.default-role.label'))
                    ->helperText(__('security::filament/clusters/manage-users.form.default-role.helper-text'))
                    ->options(Role::all()->pluck('name', 'id'))
                    ->searchable(),
                Select::make('default_company_id')->label(__('security::filament/clusters/manage-users.form.default-company.label'))
                    ->helperText(__('security::filament/clusters/manage-users.form.default-company.helper-text'))
                    ->options(Company::all()->pluck('name', 'id'))
                    ->searchable(),
            ]);
    }
}
