<?php

declare(strict_types=1);

namespace Webkul\Inventory\Filament\Clusters\Operations\Actions;

use Filament\Actions\Action;
use Livewire\Component;
use Webkul\Inventory\Enums\MoveState;
use Webkul\Inventory\Enums\OperationState;
use Webkul\Inventory\Facades\Inventory;
use Webkul\Inventory\Models\Operation;

final class CheckAvailabilityAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(__('inventories::filament/clusters/operations/actions/check-availability.label'))
            ->action(function (Operation $record, Component $livewire): void {
                $record = Inventory::checkTransferAvailability($record);

                $livewire->updateForm();
            })
            ->hidden(function (): bool {
                if (! in_array($this->getRecord()->state, [OperationState::CONFIRMED, OperationState::ASSIGNED], true)) {
                    return true;
                }

                return ! $this->getRecord()->moves->contains(fn ($move): bool => in_array($move->state, [MoveState::CONFIRMED, MoveState::PARTIALLY_ASSIGNED], true));
            });
    }

    public static function getDefaultName(): ?string
    {
        return 'inventories.operations.check_availability';
    }
}
