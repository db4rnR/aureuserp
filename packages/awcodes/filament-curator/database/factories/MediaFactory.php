<?php

namespace Awcodes\Curator\Database\Factories;

use Awcodes\Curator\Models\Media;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Storage;

class MediaFactory extends Factory
{
    protected $model = Media::class;

    protected ?string $directory = null;

    protected ?string $disk = null;

    protected ?string $type = null;

    public function definition(): array
    {
        return match ($this->getType()) {
            'svg' => $this->handleSvg(),
            'document' => $this->handleDocument(),
            'video' => $this->handleVideo(),
            default => $this->handleImage(),
        };
    }

    public function private(): MediaFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'visibility' => 'private',
            ];
        });
    }

    public function randomTimestamps(): MediaFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'created_at' => Date::now()
                    ->addDays(mt_rand(-800, 0))
                    ->addMinutes(mt_rand(0, 60 * 23))
                    ->addSeconds(mt_rand(0, 60)),
                'updated_at' => Date::now()
                    ->addDays(mt_rand(-799, 0))
                    ->addMinutes(mt_rand(0, 60 * 23))
                    ->addSeconds(mt_rand(0, 60)),
            ];
        });
    }

    public function directory(string $directory): static
    {
        $this->directory = $directory;

        return $this;
    }

    public function disk(string $disk): static
    {
        $this->disk = $disk;

        return $this;
    }

    public function type(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getDirectory(): ?string
    {
        return $this->directory ?? config('curator.directory');
    }

    public function getDisk(): ?string
    {
        return $this->disk ?? config('curator.disk');
    }

    public function getType(): string
    {
        return $this->type ?? 'image';
    }

    public function handleSvg(): array
    {
        $fakeFile = UploadedFile::fake()->create(
            name: $this->faker->word . '.svg',
            kilobytes: $this->faker->numberBetween(1, 100),
            mimeType: 'image/svg+xml',
        );

        Storage::disk($this->getDisk())->put($this->getDirectory() . '/' . $fakeFile->hashName(), $fakeFile->getContent());

        return [
            'name' => pathinfo($fakeFile->hashName(), PATHINFO_FILENAME),
            'path' => $this->getDirectory() . '/' . $fakeFile->hashName(),
            'ext' => 'svg',
            'type' => $fakeFile->getMimeType(),
            'alt' => $this->faker->words(rand(3, 8), true),
            'title' => null,
            'caption' => null,
            'description' => null,
            'width' => null,
            'height' => null,
            'disk' => $this->getDisk(),
            'directory' => $this->getDirectory(),
            'size' => $fakeFile->getSize() ?? null,
            'visibility' => 'public',
        ];
    }

    public function handleDocument(): array
    {
        $fakeFile = UploadedFile::fake()->create(
            name: $this->faker->word . '.pdf',
            kilobytes: $this->faker->numberBetween(1, 100),
            mimeType: 'application/pdf',
        );

        Storage::disk($this->getDisk())->put($this->getDirectory() . '/' . $fakeFile->hashName(), $fakeFile->getContent());

        return [
            'name' => pathinfo($fakeFile->hashName(), PATHINFO_FILENAME),
            'path' => $this->getDirectory() . '/' . $fakeFile->hashName(),
            'ext' => 'pdf',
            'type' => $fakeFile->getMimeType(),
            'alt' => $this->faker->words(rand(3, 8), true),
            'title' => null,
            'caption' => null,
            'description' => null,
            'width' => null,
            'height' => null,
            'disk' => $this->getDisk(),
            'directory' => $this->getDirectory(),
            'size' => $fakeFile->getSize() ?? null,
            'visibility' => 'public',
        ];
    }

    public function handleVideo(): array
    {
        $fakeFile = UploadedFile::fake()->create(
            name: $this->faker->word . '.mp4',
            kilobytes: $this->faker->numberBetween(1, 100),
            mimeType: 'video/mp4',
        );

        Storage::disk($this->getDisk())->put($this->getDirectory() . '/' . $fakeFile->hashName(), $fakeFile->getContent());

        return [
            'name' => pathinfo($fakeFile->hashName(), PATHINFO_FILENAME),
            'path' => $this->getDirectory() . '/' . $fakeFile->hashName(),
            'ext' => 'mp4',
            'type' => $fakeFile->getMimeType(),
            'alt' => $this->faker->words(rand(3, 8), true),
            'title' => null,
            'caption' => null,
            'description' => null,
            'width' => null,
            'height' => null,
            'disk' => $this->getDisk(),
            'directory' => $this->getDirectory(),
            'size' => $fakeFile->getSize() ?? null,
            'visibility' => 'public',
        ];
    }

    public function handleImage(): array
    {
        $width = $this->faker->numberBetween(100, 2000);
        $height = $this->faker->numberBetween(100, 2000);

        $fakeFile = UploadedFile::fake()->image(
            name: $this->faker->word . '.jpg',
            width: $width,
            height: $height,
        );

        Storage::disk($this->getDisk())->put($this->getDirectory() . '/' . $fakeFile->hashName(), $fakeFile->getContent());

        return [
            'name' => pathinfo($fakeFile->hashName(), PATHINFO_FILENAME),
            'path' => $this->getDirectory() . '/' . $fakeFile->hashName(),
            'ext' => 'jpg',
            'type' => $fakeFile->getMimeType(),
            'alt' => $this->faker->words(rand(3, 8), true),
            'title' => null,
            'caption' => null,
            'description' => null,
            'width' => $width,
            'height' => $height,
            'disk' => $this->getDisk(),
            'directory' => $this->getDirectory(),
            'size' => $fakeFile->getSize() ?? null,
            'visibility' => 'public',
        ];
    }
}
