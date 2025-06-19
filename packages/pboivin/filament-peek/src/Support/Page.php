<?php

declare(strict_types=1);

namespace Pboivin\FilamentPeek\Support;

use Livewire\Component;
use Pboivin\FilamentPeek\Exceptions\PreviewModalException;

class Page
{
    public static function supportsPreviewModal(Component $page): bool
    {
        return method_exists($page, 'openPreviewModal');
    }

    public static function ensurePreviewModalSupport(Component $page): void
    {
        if (! self::supportsPreviewModal($page)) {
            $basename = class_basename($page);
            throw new PreviewModalException("`{$basename}` class is missing the `HasPreviewModal` trait.");
        }
    }

    public static function supportsBuilderPreview(Component $page): bool
    {
        return self::supportsPreviewModal($page) && method_exists($page, 'openPreviewModalForBuidler');
    }

    public static function ensureBuilderPreviewSupport(Component $page): void
    {
        self::ensurePreviewModalSupport($page);

        if (! self::supportsBuilderPreview($page)) {
            $basename = class_basename($page);
            throw new PreviewModalException("`{$basename}` class is missing the `HasBuilderPreview` trait.");
        }
    }
}
