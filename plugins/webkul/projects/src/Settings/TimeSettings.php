<?php

declare(strict_types=1);

namespace Webkul\Project\Settings;

use Spatie\LaravelSettings\Settings;

final class TimeSettings extends Settings
{
    public bool $enable_timesheets;

    public static function group(): string
    {
        return 'time';
    }
}
