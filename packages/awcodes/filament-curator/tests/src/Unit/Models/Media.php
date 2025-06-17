<?php

use Awcodes\Curator\Models\Media;
use Illuminate\Support\Facades\Storage;

test('to array', function () {
    Storage::fake('public');

    $media = Media::factory()->create()->fresh();

    expect(array_keys($media->toArray()))->toBe([
        'id',
        'disk',
        'directory',
        'visibility',
        'name',
        'path',
        'width',
        'height',
        'size',
        'type',
        'ext',
        'alt',
        'title',
        'description',
        'caption',
        'exif',
        'curations',
        'created_at',
        'updated_at',
        'tenant_id',
        'url',
        'thumbnail_url',
        'medium_url',
        'large_url',
        'resizable',
        'size_for_humans',
        'pretty_name',
    ]);
});

test('factory creates an svg', function () {
    Storage::fake('public');

    $media = Media::factory()->type('svg')->create()->fresh();

    Storage::disk($media->disk)->assertExists($media->path);

    expect($media)
        ->ext->toBe('svg')
        ->name->not->toContain('.svg')
        ->full_path->toBe(Storage::disk($media->disk)->path($media->path));
});

test('factory creates a document', function () {
    Storage::fake('public');

    $media = Media::factory()->type('document')->create()->fresh();

    Storage::disk($media->disk)->assertExists($media->path);

    expect($media)
        ->ext->toBe('pdf')
        ->name->not->toContain('.pdf')
        ->full_path->toBe(Storage::disk($media->disk)->path($media->path));
});

test('factory creates a video', function () {
    Storage::fake('public');

    $media = Media::factory()->type('video')->create()->fresh();

    Storage::disk($media->disk)->assertExists($media->path);

    expect($media)
        ->ext->toBe('mp4')
        ->name->not->toContain('.mp4')
        ->full_path->toBe(Storage::disk($media->disk)->path($media->path));
});

test('factory creates an image', function () {
    Storage::fake('public');

    $media = Media::factory()->create()->fresh();

    Storage::disk($media->disk)->assertExists($media->path);

    expect($media)
        ->ext->toBe('jpg')
        ->name->not->toContain('.jpg')
        ->full_path->toBe(Storage::disk($media->disk)->path($media->path));
});

it('returns correct local url', function () {
    Storage::fake('public');

    $media = Media::factory()->create()->fresh();

    expect($media->url)->toBe(Storage::disk($media->disk)->url($media->path));
});

it('returns correct remote url', function () {
    Storage::fake('s3');
    config()->set('curator.disk', 's3');
    config()->set('curator.visibility', 'private');
    config()->set('curator.should_check_exists', false);

    $media = Media::factory()->create(['disk' => 's3', 'visibility' => 'private'])->fresh();

    expect($media->url)->toBe(Storage::disk($media->disk)->temporaryUrl($media->path, now()->addMinutes(5)));
});
