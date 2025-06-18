<?php

declare(strict_types=1);

namespace Hugomyb\FilamentMediaAction\Concerns;

use Closure;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;

trait HasMedia
{
    public Closure|string|null $media;

    public ?string $mediaType;

    public ?string $mime = 'unknown';

    public ?bool $preloadAuto = true;

    protected bool|Closure $hasAutoplay = false;

    protected function setUp(): void
    {
        parent::setUp();

        $this->modalSubmitAction(false);

        $this->modalCancelAction(false);

        $this->modalContent(function () {
            return $this->getContentView();
        });
    }

    public static function getDefaultName(): ?string
    {
        return 'media';
    }

    public function media(string|Closure|null $url): static
    {
        $this->media = $url;

        return $this;
    }

    public function getMedia(): ?string
    {
        return $this->evaluate($this->media, [
            ...$this->resolveDefaultClosureDependencyForEvaluationByName('record'),
            ...$this->resolveDefaultClosureDependencyForEvaluationByName('model'),
            ...$this->resolveDefaultClosureDependencyForEvaluationByName('arguments'),
            ...$this->resolveDefaultClosureDependencyForEvaluationByName('data'),
            ...$this->resolveDefaultClosureDependencyForEvaluationByName('livewire'),
        ]);
    }

    public function autoplay(bool|Closure $hasAutoplay = true): static
    {
        $this->hasAutoplay = $hasAutoplay;

        return $this;
    }

    public function hasAutoplay(): bool
    {
        return (bool) $this->evaluate($this->hasAutoplay, [
            ...$this->resolveDefaultClosureDependencyForEvaluationByName('record'),
            'mediaType' => $this->mediaType,
        ]);
    }

    public function preload(?bool $preloadAuto = true): static
    {
        $this->preloadAuto = $preloadAuto;

        return $this;
    }

    public function getContentView(): View|Htmlable
    {
        $this->mediaType = $this->detectMediaType();

        return view('filament-media-action::actions.media-modal-content', [
            'mediaType' => $this->mediaType,
            'media' => $this->getMedia(),
            'mime' => $this->mime,
            'autoplay' => $this->hasAutoplay(),
            'preload' => $this->preloadAuto,
        ]);
    }

    protected function detectMediaType(): string
    {
        return $this->getMediaType($this->getMedia());
    }

    protected function getMediaType(?string $url): ?string
    {
        // Check if the URL is a YouTube link
        if (preg_match('/(youtube\.com|youtu\.be)/', $url)) {
            return 'youtube';
        }

        // Parse the URL to remove query parameters
        $parsedUrl = parse_url($url, PHP_URL_PATH);

        // Handle cases where the URL path ends with a slash (no file)
        if (mb_substr($parsedUrl, -1) === '/') {
            $parsedUrl = mb_rtrim($parsedUrl, '/');
        }

        // Get path info from the parsed URL
        $pathInfo = pathinfo($parsedUrl);
        $extension = mb_strtolower($pathInfo['extension'] ?? '');

        // Define media types and their extensions
        $mediaTypes = [
            'audio' => ['mp3', 'wav', 'ogg', 'aac'],
            'video' => ['mp4', 'avi', 'mov', 'webm'],
            'image' => ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp'],
            'pdf' => ['pdf'],
        ];

        // Check if the extension matches any media type
        foreach ($mediaTypes as $type => $extensions) {
            if (in_array($extension, $extensions, true)) {
                $this->mime = "$type/$extension"; // Set the MIME type

                return $type;
            }
        }

        // If the extension is not found, use HTTP headers to detect the content type
        $headers = @get_headers($url, 1);
        if ($headers && is_array($headers)) {
            $headers = array_change_key_case($headers);
        }
        if ($headers && isset($headers['content-type'])) {
            $contentType = is_array($headers['content-type']) ? $headers['content-type'][0] : $headers['content-type'];
            if (mb_strpos($contentType, 'audio') !== false) {
                $this->mime = $contentType;

                return 'audio';
            }
            if (mb_strpos($contentType, 'video') !== false) {
                $this->mime = $contentType;

                return 'video';
            }
            if (mb_strpos($contentType, 'image') !== false) {
                $this->mime = $contentType;

                return 'image';
            }
            if (mb_strpos($contentType, 'pdf') !== false) {
                $this->mime = $contentType;

                return 'pdf';
            }
        }

        $this->mime = 'unknown';

        return 'unknown';
    }
}
