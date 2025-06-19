<?php

declare(strict_types=1);

namespace Webkul\Purchase\Filament\Admin\Clusters\Products\Resources;

use BackedEnum;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Schema;
use Webkul\Field\Filament\Traits\HasCustomFields;
use Webkul\Product\Filament\Resources\ProductResource as BaseProductResource;
use Webkul\Purchase\Filament\Admin\Clusters\Products;
use Webkul\Purchase\Filament\Admin\Clusters\Products\Resources\ProductResource\Pages\CreateProduct;
use Webkul\Purchase\Filament\Admin\Clusters\Products\Resources\ProductResource\Pages\EditProduct;
use Webkul\Purchase\Filament\Admin\Clusters\Products\Resources\ProductResource\Pages\ListProducts;
use Webkul\Purchase\Filament\Admin\Clusters\Products\Resources\ProductResource\Pages\ManageAttributes;
use Webkul\Purchase\Filament\Admin\Clusters\Products\Resources\ProductResource\Pages\ManageVariants;
use Webkul\Purchase\Filament\Admin\Clusters\Products\Resources\ProductResource\Pages\ManageVendors;
use Webkul\Purchase\Filament\Admin\Clusters\Products\Resources\ProductResource\Pages\ViewProduct;
use Webkul\Purchase\Models\Product;

class ProductResource extends BaseProductResource
{
    use HasCustomFields;

    protected static ?string $model = Product::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-shopping-bag';

    protected static bool $shouldRegisterNavigation = true;

    protected static ?string $cluster = Products::class;

    protected static ?int $navigationSort = 1;

    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationLabel(): string
    {
        return __('purchases::filament/admin/clusters/products/resources/product.navigation.title');
    }

    public static function form(Form $form): Form
    {
        $form = BaseProductResource::form($form);

        $components = $form->getComponents();

        $form->schema($components);

        return $form;
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        $infolist = BaseProductResource::infolist($infolist);

        $components = $infolist->getComponents();

        $infolist->schema($components);

        return $infolist;
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            ViewProduct::class,
            EditProduct::class,
            ManageAttributes::class,
            ManageVariants::class,
            ManageVendors::class,
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProducts::route('/'),
            'create' => CreateProduct::route('/create'),
            'view' => ViewProduct::route('/{record}'),
            'edit' => EditProduct::route('/{record}/edit'),
            'attributes' => ManageAttributes::route('/{record}/attributes'),
            'variants' => ManageVariants::route('/{record}/variants'),
            'vendors' => ManageVendors::route('/{record}/vendors'),
        ];
    }
}
