<?php

declare(strict_types=1);

namespace Webkul\Inventory\Filament\Clusters\Operations\Actions;

use Filament\Actions\Action;
use Livewire\Component;
use Webkul\Inventory\Enums\OperationState;
use Webkul\Inventory\Facades\Inventory;
use Webkul\Inventory\Filament\Clusters\Operations\Resources\OperationResource;
use Webkul\Inventory\Models\Operation;

final class ReturnAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(__('inventories::filament/clusters/operations/actions/return.label'))
            ->color('gray')
            ->requiresConfirmation()
            ->action(function (Operation $record, Component $livewire) {
                $newRecord = Inventory::returnTransfer($record);

                $livewire->updateForm();

                return redirect()->to(OperationResource::getUrl('edit', ['record' => $newRecord]));
            })
            ->visible(fn (): bool => $this->getRecord()->state === OperationState::DONE);
    }

    public static function getDefaultName(): ?string
    {
        return 'inventories.operations.return';
    }
}
