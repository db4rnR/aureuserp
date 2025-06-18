<?php

declare(strict_types=1);

namespace Pboivin\FilamentPeek\Tests\Unit;

use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Contracts\View\View;
use InvalidArgumentException;
use Livewire\Mechanisms\DataStore;
use Mockery;
use Pboivin\FilamentPeek\Exceptions\PreviewModalException;
use Pboivin\FilamentPeek\Tests\Unit\Fixtures\CreateRecordDummy;
use Pboivin\FilamentPeek\Tests\Unit\Fixtures\EditRecordDummy;
use Pboivin\FilamentPeek\Tests\Unit\Fixtures\ModelDummy;
use Pboivin\FilamentPeek\Tests\Unit\Fixtures\ViewRecordDummy;
use Tests\TestCase;

it('has no initial preview modal url', function (): void {
    $page = invade(new EditRecordDummy);

    expect($page->getPreviewModalUrl())->toBeNull();
});

it('has no initial preview modal view', function (): void {
    $page = invade(new EditRecordDummy);

    expect($page->getPreviewModalView())->toBeNull();
});

it('has initial preview modal title', function (): void {
    $page = invade(new EditRecordDummy);

    expect($page->getPreviewModalTitle())->not()->toBeEmpty();
});

it('has initial preview modal data record key', function (): void {
    $page = invade(new EditRecordDummy);

    expect($page->getPreviewModalDataRecordKey())->toEqual('record');
});

it('prepares preview modal data on create pages', function (): void {
    $page = invade(new CreateRecordDummy);

    $data = $page->preparePreviewModalData();

    expect($data['record'] instanceof ModelDummy)->toBeTrue();
    expect($data['isPeekPreviewModal'])->toBeTrue();
});

it('prepares preview modal data on view pages', function (): void {
    $page = invade(new ViewRecordDummy);

    $data = $page->preparePreviewModalData();

    expect($data['record'] instanceof ModelDummy)->toBeTrue();
    expect($data['isPeekPreviewModal'])->toBeTrue();
});

it('prepares preview modal data on edit pages', function (): void {
    $page = invade(new EditRecordDummy);

    $data = $page->preparePreviewModalData();

    expect($data['record'] instanceof ModelDummy)->toBeTrue();
    expect($data['isPeekPreviewModal'])->toBeTrue();
});

// @todo: Rewrite test
// it('prepares preview modal data on list pages', function () {
//     $page = invade(new Fixtures\ListRecordsDummy());
//     $data = $page->preparePreviewModalData();
//     expect($data['record'])->toBeNull();
//     expect($data['isPeekPreviewModal'])->toBeTrue();
// });

it('requires url or blade view', function (): void {
    /** @var TestCase $this */
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('Missing preview modal URL or Blade view');

    $page = invade(new EditRecordDummy);

    $page->openPreviewModal();
});

it('mutates preview modal data before opening the modal', function (): void {
    $page = invade(new class extends EditRecordDummy
    {
        protected function getPreviewModalUrl(): ?string
        {
            return 'https://example.com';
        }

        protected function mutatePreviewModalData($data): array
        {
            return array_merge($data, ['test' => 'test']);
        }
    });

    $page->openPreviewModal();

    expect($page->previewModalData['test'])->toEqual('test');
});

// @todo: Rewrite test
it('dispatches open preview modal browser event', function (): void {
    $page = invade(new class extends EditRecordDummy
    {
        protected function getPreviewModalUrl(): ?string
        {
            return 'https://example.com';
        }
    });

    $store = invade(app(DataStore::class));

    expect($store->lookup)->toBeEmpty();

    $page->openPreviewModal();

    expect($store->lookup)->not->toBeEmpty();

    foreach ($store->lookup as $item) {
        expect($item['dispatched'])->toBeArray();
        expect($item['dispatched'][0]->serialize()['name'])->toEqual('open-preview-modal');
    }
});

// @todo: Rewrite test
it('dispatches close preview modal browser event', function (): void {
    $page = invade(new class extends EditRecordDummy
    {
        protected function getPreviewModalUrl(): ?string
        {
            return 'https://example.com';
        }
    });

    $store = invade(app(DataStore::class));

    expect($store->lookup)->toBeEmpty();

    $page->closePreviewModal();

    expect($store->lookup)->not->toBeEmpty();

    foreach ($store->lookup as $item) {
        expect($item['dispatched'])->toBeArray();
        expect($item['dispatched'][0]->serialize()['name'])->toEqual('close-preview-modal');
    }
});

// @todo: Rewrite test
it('renders the preview modal view', function (): void {
    $this->mock(ViewFactory::class, function ($mock): void {
        $view = Mockery::mock(View::class, function ($mock): void {
            $mock->shouldReceive('render')->andReturn('TEST');
        });

        $mock->shouldReceive('make')->andReturn($view);
    });

    $page = invade(new class extends EditRecordDummy
    {
        protected function getPreviewModalView(): ?string
        {
            return 'preview';
        }
    });

    $store = invade(app(DataStore::class));

    expect($store->lookup)->toBeEmpty();

    $page->openPreviewModal();

    expect($store->lookup)->not->toBeEmpty();

    foreach ($store->lookup as $item) {
        expect($item['dispatched'])->toBeArray();
        expect($item['dispatched'][0]->serialize()['name'])->toEqual('open-preview-modal');
        expect($item['dispatched'][0]->serialize()['params']['iframeContent'])->toEqual('TEST');
    }
});

it('requires internal preview url for preview tab', function (): void {
    /** @var TestCase $this */
    $this->expectException(PreviewModalException::class);
    $this->expectExceptionMessage('You must enable the `internalPreviewUrl` configuration to open the preview in a new tab.');

    $page = invade(new EditRecordDummy);

    $page->openPreviewTab();
});

// @todo: Rewrite test
it('dispatches open preview tab browser event', function (): void {
    config()->set('filament-peek.internalPreviewUrl.enabled', true);

    $page = invade(new class extends EditRecordDummy
    {
        protected function getPreviewModalUrl(): ?string
        {
            return 'https://example.com';
        }
    });

    $store = invade(app(DataStore::class));

    expect($store->lookup)->toBeEmpty();

    $page->openPreviewTab();

    expect($store->lookup)->not->toBeEmpty();

    foreach ($store->lookup as $item) {
        expect($item['dispatched'])->toBeArray();
        expect($item['dispatched'][0]->serialize()['name'])->toEqual('open-preview-tab');
    }
});
