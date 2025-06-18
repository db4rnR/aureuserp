<?php

declare(strict_types=1);

namespace Webkul\Purchase\Settings;

use Spatie\LaravelSettings\Settings;

final class ProductSettings extends Settings
{
    public bool $enable_variants;

    public bool $enable_uom;

    public bool $enable_packagings;

    public static function group(): string
    {
        return 'purchases_product';
    }
}
