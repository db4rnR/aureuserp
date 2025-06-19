<?php

declare(strict_types=1);

namespace Webkul\Invoice\Filament\Clusters\Vendors\Resources\VendorResource\Pages;

use Illuminate\Contracts\Support\Htmlable;
use Webkul\Invoice\Filament\Clusters\Vendors\Resources\VendorResource;
use Webkul\Partner\Filament\Resources\PartnerResource\Pages\CreatePartner as BaseCreateVendor;

class CreateVendor extends BaseCreateVendor
{
    protected static string $resource = VendorResource::class;

    public function getTitle(): string|Htmlable
    {
        return __('invoices::filament/clusters/vendors/resources/vendor/pages/create-vendor.title');
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['sub_type'] = 'supplier';

        return $data;
    }
}
