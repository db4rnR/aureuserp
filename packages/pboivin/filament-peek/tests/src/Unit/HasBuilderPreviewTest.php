<?php

declare(strict_types=1);

namespace Pboivin\FilamentPeek\Tests\Unit;

use InvalidArgumentException;
use Livewire\Mechanisms\DataStore;
use Pboivin\FilamentPeek\Tests\Unit\Fixtures\CreateRecordDummy;
use Pboivin\FilamentPeek\Tests\Unit\Fixtures\EditRecordDummy;

it('has no initial builder preview url', function (): void {
    $page = invade(new EditRecordDummy);

    expect($page->getBuilderPreviewUrl('blocks'))->toBeNull();
});

it('has no initial builder preview view', function (): void {
    $page = invade(new EditRecordDummy);

    expect($page->getBuilderPreviewView('blocks'))->toBeNull();
});

it('has no initial builder editor schema', function (): void {
    $page = invade(new EditRecordDummy);

    expect($page->getBuilderEditorSchema('blocks'))->toBeEmpty();
});

it('has initial builder editor title', function (): void {
    $page = invade(new EditRecordDummy);

    expect($page->getBuilderEditorTitle())->not()->toBeEmpty();
});

it('has required event listener', function (): void {
    $page = invade(new EditRecordDummy);

    expect($page->getListeners())->toContain('updateBuilderFieldWithEditorData');
});

it('prepares builder preview data on create pages', function (): void {
    $page = invade(new CreateRecordDummy);

    $data = $page->prepareBuilderPreviewData([]);

    expect($data['isPeekPreviewModal'])->toBeTrue();
});

it('prepares builder preview data on edit pages', function (): void {
    $page = invade(new EditRecordDummy);

    $data = $page->prepareBuilderPreviewData([]);

    expect($data['isPeekPreviewModal'])->toBeTrue();
});

// @todo: Rewrite test
it('dispatches openBuilderEditor event', function (): void {
    $page = invade(new class extends EditRecordDummy
    {
        protected function getBuilderPreviewView(string $builderName): ?string
        {
            return 'test';
        }
    });

    $store = invade(app(DataStore::class));

    expect($store->lookup)->toBeEmpty();

    $page->openPreviewModalForBuidler('blocks');

    expect($store->lookup)->not->toBeEmpty();

    foreach ($store->lookup as $item) {
        expect($item['dispatched'])->toBeArray();
        expect($item['dispatched'][0]->serialize()['name'])->toEqual('openBuilderEditor');
        expect($item['dispatched'][0]->serialize()['params']['previewView'])->toEqual('test');
        expect($item['dispatched'][0]->serialize()['params']['modalTitle'])->toEqual('Preview');
        expect($item['dispatched'][0]->serialize()['params']['editorTitle'])->toEqual('Editor');
        expect($item['dispatched'][0]->serialize()['params']['builderName'])->toEqual('blocks');
        expect($item['dispatched'][0]->serialize()['params']['pageClass'])->not()->toBeEmpty();
        expect($item['dispatched'][0]->serialize()['params']['editorData'])->toBeArray();
    }
});

// @todo: Rewrite test
it('mutates initial builder editor data', function (): void {
    $page = invade(new class extends EditRecordDummy
    {
        protected function getBuilderPreviewView(string $builderName): ?string
        {
            return 'test';
        }

        protected function mutateInitialBuilderEditorData(string $builderName, array $data): array
        {
            $data['mutated'] = true;

            return $data;
        }
    });

    $store = invade(app(DataStore::class));

    expect($store->lookup)->toBeEmpty();

    $page->openPreviewModalForBuidler('blocks');

    expect($store->lookup)->not->toBeEmpty();

    foreach ($store->lookup as $item) {
        expect($item['dispatched'])->toBeArray();
        expect($item['dispatched'][0]->serialize()['name'])->toEqual('openBuilderEditor');
        expect($item['dispatched'][0]->serialize()['params']['editorData']['mutated'])->toBeTrue();
    }
});

it('throws an exception for missing event listener', function (): void {
    /** @var TestCase $this */
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage("Missing 'updateBuilderFieldWithEditorData' Livewire event listener");

    $page = invade(new class extends EditRecordDummy
    {
        protected function getListeners(): array
        {
            return ['test'];
        }
    });

    $page->openPreviewModalForBuidler('blocks');
});
