<?php

declare(strict_types=1);

namespace Webkul\Invoice\Filament\Clusters\Customer\Resources\CreditNotesResource\Pages;

use Filament\Pages\Enums\SubNavigationPosition;
use Webkul\Account\Filament\Resources\CreditNoteResource\Pages\EditCreditNote as BaseCreditNote;
use Webkul\Invoice\Filament\Clusters\Customer\Resources\CreditNotesResource;

final class EditCreditNotes extends BaseCreditNote
{
    protected static string $resource = CreditNotesResource::class;

    public static function getSubNavigationPosition(): SubNavigationPosition
    {
        return SubNavigationPosition::Top;
    }
}
