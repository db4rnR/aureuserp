<?php

declare(strict_types=1);

namespace Webkul\Security\Settings;

use Spatie\LaravelSettings\Settings;

final class UserSettings extends Settings
{
    public bool $enable_user_invitation;

    public bool $enable_reset_password;

    public ?int $default_role_id = null;

    public ?int $default_company_id = null;

    public static function group(): string
    {
        return 'general';
    }
}
