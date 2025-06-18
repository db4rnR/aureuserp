<?php

declare(strict_types=1);

namespace Awcodes\Curator\Curations;

final class ThumbnailPreset extends CurationPreset
{
    public function getKey(): string
    {
        return 'thumbnail';
    }

    public function getLabel(): string
    {
        return trans('curator::views.curation.thumbnail');
    }

    public function getWidth(): int
    {
        return 200;
    }

    public function getHeight(): int
    {
        return 200;
    }

    public function getFormat(): string
    {
        return 'webp';
    }

    public function getQuality(): int
    {
        return 60;
    }
}
