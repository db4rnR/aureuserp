<?php

declare(strict_types=1);

namespace Webkul\Inventory\Filament\Clusters\Operations\Actions\Print;

use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;

class PackageAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(__('inventories::filament/clusters/operations/actions/print/packages.label'))
            ->action(function ($record) {
                $packages = $record->packages()->distinct()->get();

                $pdf = Pdf::loadView('inventories::filament.clusters.products.packages.actions.print-with-content', [
                    'records' => $packages,
                ]);

                $pdf->setPaper('a4', 'portrait');

                return response()->streamDownload(function () use ($pdf): void {
                    echo $pdf->output();
                }, 'Package-'.str_replace('/', '_', $record->name).'.pdf');
            });
    }

    public static function getDefaultName(): ?string
    {
        return 'inventories.operations.print.package';
    }
}
