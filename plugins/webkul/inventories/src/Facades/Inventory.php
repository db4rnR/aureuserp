<?php

namespace Webkul\Inventory\Facades;

use Illuminate\Support\Facades\Facade;

class Inventory extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'inventory';
    }
}
