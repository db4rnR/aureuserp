<?php

declare(strict_types=1);

namespace Webkul\TimeOff\Filament\Clusters\Reporting\Resources;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Webkul\TimeOff\Filament\Clusters\Management\Resources\TimeOffResource;
use Webkul\TimeOff\Filament\Clusters\Reporting;
use Webkul\TimeOff\Filament\Clusters\Reporting\Resources\ByEmployeeResource\Pages\CreateByEmployee;
use Webkul\TimeOff\Filament\Clusters\Reporting\Resources\ByEmployeeResource\Pages\EditByEmployee;
use Webkul\TimeOff\Filament\Clusters\Reporting\Resources\ByEmployeeResource\Pages\ListByEmployees;
use Webkul\TimeOff\Models\Leave;

class ByEmployeeResource extends Resource
{
    protected static ?string $model = Leave::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static ?string $cluster = Reporting::class;

    public static function getModelLabel(): string
    {
        return __('time-off::filament/clusters/reporting/resources/by-employee.title');
    }

    public static function getNavigationLabel(): string
    {
        return __('time-off::filament/clusters/reporting/resources/by-employee.navigation.title');
    }

    public static function form(Form $form): Form
    {
        return TimeOffResource::form($form);
    }

    public static function table(Table $table): Table
    {
        return TimeOffResource::table($table)
            ->defaultGroup('employee.name');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListByEmployees::route('/'),
            'create' => CreateByEmployee::route('/create'),
            'edit' => EditByEmployee::route('/{record}/edit'),
        ];
    }
}
