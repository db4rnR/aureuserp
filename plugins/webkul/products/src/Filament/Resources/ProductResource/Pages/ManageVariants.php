<?php

declare(strict_types=1);

namespace Webkul\Product\Filament\Resources\ProductResource\Pages;

use BackedEnum;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Forms\Form;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Arr;
use Webkul\Product\Filament\Resources\ProductResource;

class ManageVariants extends ManageRelatedRecords
{
    protected static string $resource = ProductResource::class;

    protected static string $relationship = 'variants';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function getSubNavigationPosition(): SubNavigationPosition
    {
        return SubNavigationPosition::Top;
    }

    public static function getNavigationLabel(): string
    {
        return __('products::filament/resources/product/pages/manage-variants.title');
    }

    public function form(Form $form): Form
    {
        return ProductResource::form($form);
    }

    public function table(Table $table): Table
    {
        $table = ProductResource::table($table);

        [$actions] = $table->getActions();

        $flatActions = $actions->getFlatActions();

        if (isset($flatActions['edit'])) {
            $flatActions['edit']
                ->modalWidth(Width::SevenExtraLarge);
        }

        if (isset($flatActions['view'])) {
            $flatActions['view']
                ->modalWidth(Width::SevenExtraLarge);
        }

        $table->columns(Arr::except($table->getColumns(), ['variants_count']));

        $table->columns([
            TextColumn::make('combinations')->label(__('products::filament/resources/product/pages/manage-variants.table.columns.variant-values'))
                ->state(fn ($record) => $record->combinations->map(function ($combination) {
                    $attributeName = $combination->productAttributeValue?->attribute?->name;
                    $optionName = $combination->productAttributeValue?->attributeOption?->name;

                    return $attributeName && $optionName ? "{$attributeName}: {$optionName}" : $optionName;
                }))
                ->badge()
                ->sortable(),
            ...$table->getColumns(),
        ]);

        $table->modelLabel(__('products::filament/resources/product/pages/manage-variants.title'));

        return $table;
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return ProductResource::infolist($infolist);
    }
}
