<?php

namespace Webkul\Inventory\Filament\Clusters\Operations\Resources;

use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Webkul\Inventory\Filament\Clusters\Operations;
use Webkul\Inventory\Filament\Clusters\Operations\Resources\ReplenishmentResource\Pages;
use Webkul\Inventory\Models\OrderPoint;

class ReplenishmentResource extends Resource
{
    protected static ?string $model = OrderPoint::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrows-up-down';

    protected static ?int $navigationSort = 4;

    // Todo: Remove this when completed
    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $cluster = Operations::class;

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function getNavigationLabel(): string
    {
        return __('inventories::filament/clusters/operations/resources/replenishment.navigation.title');
    }

    public static function getNavigationGroup(): string
    {
        return __('inventories::filament/clusters/operations/resources/replenishment.navigation.group');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([

            ])
            ->groups(
                collect([
                ])->filter(function ($group) {
                    return match ($group->getId()) {
                        default        => true
                    };
                })->all()
            )
            ->filters([
                Tables\Filters\QueryBuilder::make()
                    ->constraints(collect([
                    ])->filter()->values()->all()),
            ], layout: \Filament\Tables\Enums\FiltersLayout::Modal)
            ->filtersTriggerAction(
                fn (Tables\Actions\Action $action) => $action
                    ->slideOver(),
            )
            ->filtersFormColumns(2)
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label(__('inventories::filament/clusters/operations/resources/replenishment.table.header-actions.create.label'))
                    ->icon('heroicon-o-plus-circle')
                    ->mutateFormDataUsing(function (array $data): array {

                        return $data;
                    })
                    ->before(function (array $data) {})
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('inventories::filament/clusters/operations/resources/replenishment.table.header-actions.create.notification.title'))
                            ->body(__('inventories::filament/clusters/operations/resources/replenishment.table.header-actions.create.notification.body')),
                    ),
            ])
            ->actions([
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ManageReplenishment::route('/'),
        ];
    }
}
