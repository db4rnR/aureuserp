<?php

declare(strict_types=1);

namespace Webkul\Sale\Facades;

use Illuminate\Support\Facades\Facade;

final class SaleOrder extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'sale';
    }
}
