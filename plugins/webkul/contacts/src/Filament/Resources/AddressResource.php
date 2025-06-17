<?php

namespace Webkul\Contact\Filament\Resources;

use Webkul\Partner\Filament\Resources\AddressResource as BaseAddressResource;
use Webkul\Partner\Models\Partner;

class AddressResource extends BaseAddressResource
{
    protected static ?string $model = Partner::class;

    protected static bool $shouldRegisterNavigation = false;
}
