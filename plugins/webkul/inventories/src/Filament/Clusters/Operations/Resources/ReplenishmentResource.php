<?php

declare(strict_types=1);

namespace Webkul\Inventory\Filament\Clusters\Operations\Resources;

use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Table;
use Webkul\Inventory\Filament\Clusters\Operations;
use Webkul\Inventory\Filament\Clusters\Operations\Resources\ReplenishmentResource\Pages\ManageReplenishment;
use Webkul\Inventory\Models\OrderPoint;

final class ReplenishmentResource extends Resource
{
    protected static ?string $model = OrderPoint::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-arrows-up-down';

    protected static ?int $navigationSort = 4;

    // Todo: Remove this when completed
    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $cluster = Operations::class;

    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function getNavigationLabel(): string
    {
        return __('inventories::filament/clusters/operations/resources/replenishment.navigation.title');
    }

    public static function getNavigationGroup(): string
    {
        return __('inventories::filament/clusters/operations/resources/replenishment.navigation.group');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
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
                ])->filter(fn ($group): true => match ($group->getId()) {
                    default => true
                })->all()
            )
            ->filters([
                QueryBuilder::make()
                    ->constraints(collect([
                    ])->filter()->values()->all()),
            ], layout: FiltersLayout::Modal)
            ->filtersTriggerAction(
                fn (Action $action): Action => $action
                    ->slideOver(),
            )
            ->filtersFormColumns(2)
            ->headerActions([
                CreateAction::make()
                    ->label(__('inventories::filament/clusters/operations/resources/replenishment.table.header-actions.create.label'))
                    ->icon('heroicon-o-plus-circle')
                    ->mutateDataUsing(fn (array $data): array => $data)
                    ->before(function (array $data): void {})
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('inventories::filament/clusters/operations/resources/replenishment.table.header-actions.create.notification.title'))
                            ->body(__('inventories::filament/clusters/operations/resources/replenishment.table.header-actions.create.notification.body')),
                    ),
            ])
            ->recordActions([
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageReplenishment::route('/'),
        ];
    }
}
