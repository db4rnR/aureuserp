<?php

declare(strict_types=1);

namespace Kirschbaum\Commentions;

use Filament\Models\Contracts\HasName;
use Kirschbaum\Commentions\Contracts\Commenter;

final class Manager
{
    public static function getName(Commenter $mentionable)
    {
        if (method_exists($mentionable, 'getCommenterName')) {
            return call_user_func_array([$mentionable, 'getCommenterName'], []);
        }

        if ($mentionable instanceof HasName) {
            return $mentionable->getFilamentName();
        }

        return $mentionable->name;
    }
}
