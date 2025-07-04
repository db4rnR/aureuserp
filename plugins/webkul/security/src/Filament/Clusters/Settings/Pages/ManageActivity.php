<?php

declare(strict_types=1);

namespace Webkul\Security\Filament\Clusters\Settings\Pages;

use BackedEnum;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Pages\SettingsPage;
use Filament\Forms\Components\Actions;
use Filament\Forms\Form;
use Webkul\Security\Settings\UserSettings;
use Webkul\Support\Filament\Clusters\Settings;

class ManageActivity extends SettingsPage
{
    use HasPageShield;

    protected static ?string $cluster = Settings::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static string $settings = UserSettings::class;

    public static function getNavigationGroup(): string
    {
        return __('security::filament/clusters/manage-activity.group');
    }

    public static function getNavigationLabel(): string
    {
        return __('security::filament/clusters/manage-activity.navigation.label');
    }

    public function getBreadcrumbs(): array
    {
        return [
            __('security::filament/clusters/manage-activity.breadcrumb'),
        ];
    }

    public function getTitle(): string
    {
        return __('security::filament/clusters/manage-activity.title');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Placeholder::make('activity_description')->label(__('security::filament/clusters/manage-activity.form.activity-description.label'))
                    ->content(__('security::filament/clusters/manage-activity.form.activity-description.content')),
                Actions::make([
                    Action::make('manageActivityTypes')->label(__('security::filament/clusters/manage-activity.form.actions.manage-activity-types.label'))
                        ->icon('heroicon-o-arrow-top-right-on-square')
                        ->link()
                        ->url(route('filament.admin.resources.settings.activity-types.index')),
                ]),
            ])->columns(1);
    }

    public function getFormActions(): array
    {
        return [];
    }
}
