<?php

declare(strict_types=1);

namespace Webkul\Account\Filament\Resources\TaxResource\Pages;

use BackedEnum;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Resources\Pages\ManageRelatedRecords;
use Webkul\Account\Enums\DocumentType;
use Webkul\Account\Filament\Resources\TaxResource;
use Webkul\Account\Traits\TaxPartition;

final class ManageDistributionForInvoice extends ManageRelatedRecords
{
    use TaxPartition;

    protected static string $resource = TaxResource::class;

    protected static string $relationship = 'distributionForInvoice';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document';

    public static function getSubNavigationPosition(): SubNavigationPosition
    {
        return SubNavigationPosition::Top;
    }

    public static function getNavigationLabel(): string
    {
        return __('accounts::filament/resources/tax/pages/manage-distribution-for-invoice.navigation.title');
    }

    public function getDocumentType(): string
    {
        return DocumentType::INVOICE->value;
    }
}
