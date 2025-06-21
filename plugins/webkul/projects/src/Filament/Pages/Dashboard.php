<?php

declare(strict_types=1);

namespace Webkul\Project\Filament\Pages;

use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Forms\Components\Section;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\View\LegacyComponents\Widget;
use Webkul\Partner\Models\Partner;
use Webkul\Project\Filament\Widgets\StatsOverviewWidget;
use Webkul\Project\Filament\Widgets\TaskByStageChart;
use Webkul\Project\Filament\Widgets\TaskByStateChart;
use Webkul\Project\Filament\Widgets\TopAssigneesWidget;
use Webkul\Project\Filament\Widgets\TopProjectsWidget;
use Webkul\Project\Models\Project;
use Webkul\Project\Models\Tag;
use Webkul\Security\Models\User;
use Webkul\Support\Filament\Clusters\Dashboard as DashboardCluster;

class Dashboard extends BaseDashboard
{
    use BaseDashboard\Concerns\HasFiltersForm;

    protected static string $routePath = 'project';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-folder';

    protected static ?string $cluster = DashboardCluster::class;

    public static function getNavigationLabel(): string
    {
        return __('projects::filament/pages/dashboard.navigation.title');
    }

    public function filtersForm(Form $form): Form
    {
        return $form
            ->components([
                Section::make()->schema([
                        Select::make('selectedProjects')->label(__('projects::filament/pages/dashboard.filters-form.project'))
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->options(fn () => Project::pluck('name', 'id'))
                            ->reactive(),
                        Select::make('selectedAssignees')->label(__('projects::filament/pages/dashboard.filters-form.assignees'))
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->options(fn () => User::pluck('name', 'id'))
                            ->reactive(),
                        Select::make('selectedTags')->label(__('projects::filament/pages/dashboard.filters-form.tags'))
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->options(fn () => Tag::pluck('name', 'id'))
                            ->reactive(),
                        Select::make('selectedPartners')->label(__('projects::filament/pages/dashboard.filters-form.customer'))
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->options(fn () => Partner::pluck('name', 'id'))
                            ->reactive(),
                        DatePicker::make('startDate')->label(__('projects::filament/pages/dashboard.filters-form.start-date'))
                            ->maxDate(fn (Get $get) => $get('endDate') ?: now())
                            ->default(now()->subMonth()->format('Y-m-d'))
                            ->native(false),
                        DatePicker::make('endDate')->label(__('projects::filament/pages/dashboard.filters-form.end-date'))
                            ->minDate(fn (Get $get) => $get('startDate') ?: now())
                            ->maxDate(now())
                            ->default(now())
                            ->native(false),
                    ])
                    ->columns(3),
            ]);
    }

    /**
     * @return array<class-string<Widget> | WidgetConfiguration>
     */
    public function getWidgets(): array
    {
        return [
            StatsOverviewWidget::class,
            TaskByStageChart::class,
            TaskByStateChart::class,
            TopAssigneesWidget::class,
            TopProjectsWidget::class,
        ];
    }
}
