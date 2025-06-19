<?php

declare(strict_types=1);

namespace Pboivin\FilamentPeek;

use Illuminate\Support\Facades\Cache;

class CachedPreview
{
    public static ?string $cacheStore = null;

    public function __construct(
        public string $pageClass,
        public string $view,
        public array $data,
    ) {}

    public static function make(
        string $pageClass,
        string $view,
        array $data,
    ): self {
        return new self($pageClass, $view, $data);
    }

    public static function get(string $token): ?self
    {
        return Cache::store(self::$cacheStore)->get("filament-peek-preview-{$token}");
    }

    public function render(): string
    {
        return $this->pageClass::renderPreviewModalView($this->view, $this->data);
    }

    public function put(string $token, int $ttl = 60): bool
    {
        return Cache::store(self::$cacheStore)->put("filament-peek-preview-{$token}", $this, $ttl);
    }
}
