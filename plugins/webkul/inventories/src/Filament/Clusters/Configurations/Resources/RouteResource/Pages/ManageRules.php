<?php

declare(strict_types=1);

namespace Webkul\Inventory\Filament\Clusters\Configurations\Resources\RouteResource\Pages;

use BackedEnum;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Forms\Form;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Webkul\Inventory\Filament\Clusters\Configurations\Resources\RouteResource;
use Webkul\Inventory\Filament\Clusters\Configurations\Resources\RuleResource;

class ManageRules extends ManageRelatedRecords
{
    protected static string $resource = RouteResource::class;

    protected static string $relationship = 'rules';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-check';

    public static function getNavigationLabel(): string
    {
        return __('inventories::filament/clusters/configurations/resources/route/pages/manage-rules.title');
    }

    public function form(Form $form): Form
    {
        return RuleResource::form($form);
    }

    public function table(Table $table): Table
    {
        return RuleResource::table($table)
            ->columns([
                TextColumn::make('action')->searchable(),
                TextColumn::make('sourceLocation.full_name')->searchable(),
                TextColumn::make('destinationLocation.full_name')->searchable(),
            ])
            ->headerActions([
                CreateAction::make()->label(__('inventories::filament/clusters/configurations/resources/route/pages/manage-rules.table.header-actions.create.label'))
                    ->icon('heroicon-o-plus-circle')
                    ->fillForm(fn (array $arguments): array => [
                        'route_id' => $this->getOwnerRecord()->id,
                    ])
                    ->mutateDataUsing(function (array $data): array {
                        $data['creator_id'] = Auth::id();

                        $data['company_id'] ??= Auth::user()->default_company_id;

                        return $data;
                    })
                    ->successNotification(
                        Notification::make()->success()
                            ->title(__('inventories::filament/clusters/configurations/resources/route/pages/manage-rules.table.header-actions.create.notification.title'))
                            ->body(__('inventories::filament/clusters/configurations/resources/route/pages/manage-rules.table.header-actions.create.notification.body')),
                    ),
            ]);
    }
}
