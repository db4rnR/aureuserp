<?php

declare(strict_types=1);

namespace Webkul\Invoice\Filament\Clusters\Customer\Resources\CreditNotesResource\Pages;

use Webkul\Account\Filament\Resources\CreditNoteResource\Pages\CreateCreditNote as BaseCreateInvoice;
use Webkul\Invoice\Filament\Clusters\Customer\Resources\CreditNotesResource;

final class CreateCreditNotes extends BaseCreateInvoice
{
    protected static string $resource = CreditNotesResource::class;
}
