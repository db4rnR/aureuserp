<?php

declare(strict_types=1);

namespace Webkul\Inventory\Filament\Clusters\Operations\Actions;

use Filament\Actions\Action;
use Livewire\Component;
use Webkul\Inventory\Enums\OperationState;
use Webkul\Inventory\Facades\Inventory;
use Webkul\Inventory\Models\Operation;

final class CancelAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(__('inventories::filament/clusters/operations/actions/cancel.label'))
            ->color('gray')
            ->action(function (Operation $record, Component $livewire): void {
                $record = Inventory::cancelTransfer($record);

                $livewire->updateForm();
            })
            ->visible(fn (): bool => ! in_array($this->getRecord()->state, [
                OperationState::DONE,
                OperationState::CANCELED,
            ], true));
    }

    public static function getDefaultName(): ?string
    {
        return 'inventories.operations.cancel';
    }
}
