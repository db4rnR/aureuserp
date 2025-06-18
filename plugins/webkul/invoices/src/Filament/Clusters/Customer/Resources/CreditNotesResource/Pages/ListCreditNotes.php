<?php

declare(strict_types=1);

namespace Webkul\Invoice\Filament\Clusters\Customer\Resources\CreditNotesResource\Pages;

use Webkul\Account\Filament\Resources\CreditNoteResource\Pages\ListCreditNotes as BaseListInvoices;
use Webkul\Invoice\Filament\Clusters\Customer\Resources\CreditNotesResource;

final class ListCreditNotes extends BaseListInvoices
{
    protected static string $resource = CreditNotesResource::class;
}
