<?php

declare(strict_types=1);

namespace Webkul\Sale\Filament\Clusters\Orders\Resources\QuotationResource\Actions;

use Filament\Actions\Action;

class PreviewAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(__('sales::filament/clusters/orders/resources/quotation/actions/preview.title'))
            ->modalFooterActions(fn ($record): array => [])
            ->modalContent(fn ($record) => view('sales::sales.quotation', ['record' => $record]))
            ->color('gray');
    }

    public static function getDefaultName(): ?string
    {
        return 'orders.sales.preview-quotation';
    }
}
