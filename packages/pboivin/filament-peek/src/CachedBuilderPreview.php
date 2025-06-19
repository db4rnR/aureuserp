<?php

declare(strict_types=1);

namespace Pboivin\FilamentPeek;

class CachedBuilderPreview extends CachedPreview
{
    public static function make(
        string $pageClass,
        string $view,
        array $data,
    ): self {
        return new self($pageClass, $view, $data);
    }

    public function render(): string
    {
        return $this->pageClass::renderBuilderPreview($this->view, $this->data);
    }
}
