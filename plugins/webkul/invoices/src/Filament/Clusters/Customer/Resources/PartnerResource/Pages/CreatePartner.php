<?php

declare(strict_types=1);

namespace Webkul\Invoice\Filament\Clusters\Customer\Resources\PartnerResource\Pages;

use Illuminate\Contracts\Support\Htmlable;
use Webkul\Invoice\Filament\Clusters\Customer\Resources\PartnerResource;
use Webkul\Invoice\Filament\Clusters\Vendors\Resources\VendorResource\Pages\CreateVendor as BaseCreatePartner;

final class CreatePartner extends BaseCreatePartner
{
    protected static string $resource = PartnerResource::class;

    public function getTitle(): string|Htmlable
    {
        return __('Customer');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['sub_type'] = 'customer';

        return $data;
    }
}
