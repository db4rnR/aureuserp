<?php

declare(strict_types=1);

namespace Webkul\Account\Facades;

use Illuminate\Support\Facades\Facade;

class Tax extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'tax';
    }
}
