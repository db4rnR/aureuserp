<?php

namespace Webkul\Account\Filament\Resources\TaxResource\Pages;

use Webkul\Account\Enums\DocumentType;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Resources\Pages\ManageRelatedRecords;
use Webkul\Account\Enums;
use Webkul\Account\Filament\Resources\TaxResource;
use Webkul\Account\Traits\TaxPartition;

class ManageDistributionForRefund extends ManageRelatedRecords
{
    use TaxPartition;

    protected static string $resource = TaxResource::class;

    protected static string $relationship = 'distributionForRefund';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document';

    public function getDocumentType(): string
    {
        return DocumentType::REFUND->value;
    }

    static function getSubNavigationPosition(): SubNavigationPosition
    {
        return SubNavigationPosition::Top;
    }

    public static function getNavigationLabel(): string
    {
        return __('accounts::filament/resources/tax/pages/manage-distribution-for-refund.navigation.title');
    }
}
