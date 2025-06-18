<?php

declare(strict_types=1);

namespace Awcodes\Curator\PathGenerators;

use Awcodes\Curator\PathGenerators\Contracts\PathGenerator;
use Illuminate\Support\Facades\Auth;

final class UserPathGenerator implements PathGenerator
{
    public function getPath(?string $baseDir = null): string
    {
        $user = Auth::user();

        return ($baseDir ? $baseDir.'/' : '').$user->getAuthIdentifier();
    }
}
