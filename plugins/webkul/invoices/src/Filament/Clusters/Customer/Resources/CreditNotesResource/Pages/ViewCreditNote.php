<?php

declare(strict_types=1);

namespace Webkul\Invoice\Filament\Clusters\Customer\Resources\CreditNotesResource\Pages;

use Filament\Pages\Enums\SubNavigationPosition;
use Webkul\Account\Filament\Resources\CreditNoteResource\Pages\ViewCreditNote as BaseViewInvoice;
use Webkul\Invoice\Filament\Clusters\Customer\Resources\CreditNotesResource;

final class ViewCreditNote extends BaseViewInvoice
{
    protected static string $resource = CreditNotesResource::class;

    public static function getSubNavigationPosition(): SubNavigationPosition
    {
        return SubNavigationPosition::Top;
    }
}
