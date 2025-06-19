<?php

declare(strict_types=1);

namespace Pboivin\FilamentPeek\Support;

use Illuminate\Support\Facades\View as BladeView;

class View
{
    public const PREVIEW_ACTION_SETUP_HOOK = '__is_filament_peek_preview_action_setup';

    public const BUILDER_PREVIEW_SETUP_HOOK = '__is_filament_peek_builder_preview_setup';

    public static function setupPreviewModal(): void
    {
        BladeView::share(self::PREVIEW_ACTION_SETUP_HOOK, true);
    }

    public static function needsPreviewModal(): bool
    {
        return (bool) BladeView::shared(self::PREVIEW_ACTION_SETUP_HOOK);
    }

    public static function setupBuilderEditor(): void
    {
        BladeView::share(self::BUILDER_PREVIEW_SETUP_HOOK, true);
    }

    public static function needsBuilderEditor(): bool
    {
        return (bool) BladeView::shared(self::BUILDER_PREVIEW_SETUP_HOOK);
    }
}
