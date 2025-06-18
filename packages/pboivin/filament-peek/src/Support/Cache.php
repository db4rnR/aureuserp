<?php

declare(strict_types=1);

namespace Pboivin\FilamentPeek\Support;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

final class Cache
{
    public function createPreviewToken(): string
    {
        return md5('preview-'.Auth::user()->getAuthIdentifier().Config::get('app.key', ''));
    }
}
