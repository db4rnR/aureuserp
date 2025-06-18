<?php

declare(strict_types=1);

namespace Webkul\Contact\Filament\Resources;

use Webkul\Partner\Filament\Resources\AddressResource as BaseAddressResource;
use Webkul\Partner\Models\Partner;

final class AddressResource extends BaseAddressResource
{
    protected static ?string $model = Partner::class;

    protected static bool $shouldRegisterNavigation = false;
}
