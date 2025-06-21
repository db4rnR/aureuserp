<?php

declare(strict_types=1);

namespace Webkul\Project\Filament\Resources\ProjectResource\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Webkul\Project\Filament\Clusters\Configurations\Resources\TaskStageResource;

class TaskStagesRelationManager extends RelationManager
{
    protected static string $relationship = 'taskStages';

    public function form(Form $form): Form
    {
        return TaskStageResource::form($form);
    }

    public function table(Table $table): Table
    {
        return TaskStageResource::table($table)
            ->filters([])
            ->groups([])
            ->headerActions([
                CreateAction::make()->label(__('projects::filament/resources/project/relation-managers/task-stages.table.header-actions.create.label'))
                    ->icon('heroicon-o-plus-circle')
                    ->mutateDataUsing(function (array $data): array {
                        $data['creator_id'] = Auth::id();

                        return $data;
                    })
                    ->successNotification(
                        Notification::make()->success()
                            ->title(__('projects::filament/resources/project/relation-managers/task-stages.table.header-actions.create.notification.title'))
                            ->body(__('projects::filament/resources/project/relation-managers/task-stages.table.header-actions.create.notification.body')),
                    ),
            ]);
    }
}
