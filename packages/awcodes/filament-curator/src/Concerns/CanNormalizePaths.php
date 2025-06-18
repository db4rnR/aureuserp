<?php

declare(strict_types=1);

namespace Awcodes\Curator\Concerns;

use Illuminate\Support\Str;

trait CanNormalizePaths
{
    public function normalizePath(string $path): string
    {
        // normalization /path//to/dir/ --> path/to/dir
        $path = preg_replace('#/+#', '/', $path);
        if (Str::startsWith($path, '/')) {
            $path = mb_substr($path, 1);
        }
        if (Str::endsWith($path, '/')) {
            $path = mb_substr($path, 0, mb_strlen($path) - 1);
        }

        return $path;
    }
}
