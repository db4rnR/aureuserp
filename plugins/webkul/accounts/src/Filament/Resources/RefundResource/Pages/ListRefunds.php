<?php

declare(strict_types=1);

namespace Webkul\Account\Filament\Resources\RefundResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Webkul\Account\Filament\Resources\RefundResource;

final class ListRefunds extends ListRecords
{
    protected static string $resource = RefundResource::class;
}
