<?php

declare(strict_types=1);

namespace Webkul\Recruitment\Filament\Clusters\Configurations\Resources;

use BackedEnum;
use Filament\Tables\Table;
use Webkul\Recruitment\Filament\Clusters\Configurations;
use Webkul\Recruitment\Filament\Clusters\Configurations\Resources\ActivityTypeResource\Pages\CreateActivityType;
use Webkul\Recruitment\Filament\Clusters\Configurations\Resources\ActivityTypeResource\Pages\EditActivityType;
use Webkul\Recruitment\Filament\Clusters\Configurations\Resources\ActivityTypeResource\Pages\ListActivityTypes;
use Webkul\Recruitment\Filament\Clusters\Configurations\Resources\ActivityTypeResource\Pages\ViewActivityType;
use Webkul\Recruitment\Models\ActivityType;
use Webkul\Support\Filament\Resources\ActivityTypeResource as BaseActivityTypeResource;

class ActivityTypeResource extends BaseActivityTypeResource
{
    protected static ?string $model = ActivityType::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Configurations::class;

    protected static bool $shouldRegisterNavigation = true;

    public static function getNavigationGroup(): string
    {
        return __('recruitments::filament/clusters/configurations/resources/activity-type.navigation.group');
    }

    public static function table(Table $table): Table
    {
        return BaseActivityTypeResource::table($table)
            ->modifyQueryUsing(fn ($query) => $query->where('plugin', 'recruitments'));
    }

    public static function getPages(): array
    {
        return [
            'index' => ListActivityTypes::route('/'),
            'create' => CreateActivityType::route('/create'),
            'edit' => EditActivityType::route('/{record}/edit'),
            'view' => ViewActivityType::route('/{record}'),
        ];
    }
}
