<?php

declare(strict_types=1);

namespace Webkul\Inventory\Filament\Clusters\Operations\Actions\Print;

use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;

final class DeliverySlipAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(__('inventories::filament/clusters/operations/actions/print/delivery-slip.label'))
            ->action(function ($record) {
                $pdf = Pdf::loadView('inventories::filament.clusters.operations.actions.print-delivery-slip', [
                    'records' => [$record],
                ]);

                $pdf->setPaper('a4', 'portrait');

                return response()->streamDownload(function () use ($pdf): void {
                    echo $pdf->output();
                }, 'Delivery Slip-'.str_replace('/', '_', $record->name).'.pdf');
            });
    }

    public static function getDefaultName(): ?string
    {
        return 'inventories.operations.print.delivery-slip';
    }
}
