<?php

declare(strict_types=1);

namespace Webkul\Website\Settings;

use Spatie\LaravelSettings\Settings;

class ContactSettings extends Settings
{
    public ?string $email = null;

    public ?string $phone = null;

    public ?string $twitter = null;

    public ?string $facebook = null;

    public ?string $instagram = null;

    public ?string $whatsapp = null;

    public ?string $youtube = null;

    public ?string $linkedin = null;

    public ?string $pinterest = null;

    public ?string $tiktok = null;

    public ?string $github = null;

    public ?string $slack = null;

    public static function group(): string
    {
        return 'website_contact';
    }
}
