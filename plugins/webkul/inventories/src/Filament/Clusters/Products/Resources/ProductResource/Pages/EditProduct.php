<?php

namespace Webkul\Inventory\Filament\Clusters\Products\Resources\ProductResource\Pages;

use Filament\Actions;
use Filament\Forms;
use Illuminate\Support\Facades\Auth;
use Webkul\Inventory\Enums;
use Webkul\Inventory\Filament\Clusters\Products\Resources\ProductResource;
use Webkul\Inventory\Models\Location;
use Webkul\Inventory\Models\Product;
use Webkul\Inventory\Models\ProductQuantity;
use Webkul\Inventory\Models\Warehouse;
use Webkul\Inventory\Settings\OperationSettings;
use Webkul\Inventory\Settings\TraceabilitySettings;
use Webkul\Inventory\Settings\WarehouseSettings;
use Webkul\Product\Filament\Resources\ProductResource\Pages\EditProduct as BaseEditProduct;

class EditProduct extends BaseEditProduct
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return array_merge([
            Actions\Action::make('updateQuantity')
                ->label(__('inventories::filament/clusters/products/resources/product/pages/edit-product.header-actions.update-quantity.label'))
                ->modalHeading(__('inventories::filament/clusters/products/resources/product/pages/edit-product.header-actions.update-quantity.modal-heading'))
                ->form(fn (Product $record): array => [
                    Forms\Components\Select::make('product_id')
                        ->label(__('inventories::filament/clusters/products/resources/product/pages/edit-product.header-actions.update-quantity.form.fields.product'))
                        ->required()
                        ->options($record->variants->pluck('name', 'id'))
                        ->searchable()
                        ->live()
                        ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
                            $product = Product::find($get('product_id'));

                            $set('quantity', $product?->on_hand_quantity ?? 0);
                        })
                        ->visible((bool) $record->is_configurable),
                    Forms\Components\TextInput::make('quantity')
                        ->label(__('inventories::filament/clusters/products/resources/product/pages/edit-product.header-actions.update-quantity.form.fields.on-hand-qty'))
                        ->numeric()
                        ->maxValue(99999999999)
                        ->required()
                        ->live()
                        ->default(fn () => ! $record->is_configurable ? $record->on_hand_quantity : 0),
                ])
                ->modalSubmitActionLabel(__('inventories::filament/clusters/products/resources/product/pages/edit-product.header-actions.update-quantity.modal-submit-action-label'))
                ->visible($this->getRecord()->is_storable)
                ->beforeFormFilled(function (
                    OperationSettings $operationSettings,
                    TraceabilitySettings $traceabilitySettings,
                    WarehouseSettings $warehouseSettings,
                    Product $record
                ) {
                    if (
                        $operationSettings->enable_packages
                        || $warehouseSettings->enable_locations
                        || (
                            $traceabilitySettings->enable_lots_serial_numbers
                            && $record->tracking != Enums\ProductTracking::QTY
                        )
                    ) {
                        return redirect()->to(ProductResource::getUrl('quantities', ['record' => $record]));
                    }
                })
                ->action(function (Product $record, array $data): void {
                    if (isset($data['product_id'])) {
                        $record = Product::find($data['product_id']);
                    }

                    $previousQuantity = $record->on_hand_quantity;

                    if ($previousQuantity == $data['quantity']) {
                        return;
                    }

                    $warehouse = Warehouse::first();

                    $adjustmentLocation = Location::where('type', Enums\LocationType::INVENTORY)
                        ->where('is_scrap', false)
                        ->first();

                    $currentQuantity = $data['quantity'] - $previousQuantity;

                    if ($currentQuantity < 0) {
                        $sourceLocationId = $data['location_id'] ?? $warehouse->lot_stock_location_id;

                        $destinationLocationId = $adjustmentLocation->id;
                    } else {
                        $sourceLocationId = $data['location_id'] ?? $adjustmentLocation->id;

                        $destinationLocationId = $warehouse->lot_stock_location_id;
                    }

                    $productQuantity = ProductQuantity::where('product_id', $record->id)
                        ->where('location_id', $data['location_id'] ?? $warehouse->lot_stock_location_id)
                        ->first();

                    if ($productQuantity) {
                        $productQuantity->update(['quantity' => $data['quantity']]);
                    } else {
                        $productQuantity = ProductQuantity::create([
                            'product_id'        => $record->id,
                            'company_id'        => $record->company_id,
                            'location_id'       => $data['location_id'] ?? $warehouse->lot_stock_location_id,
                            'package_id'        => $data['package_id'] ?? null,
                            'lot_id'            => $data['lot_id'] ?? null,
                            'quantity'          => $data['quantity'],
                            'reserved_quantity' => 0,
                            'incoming_at'       => now(),
                            'creator_id'        => Auth::id(),
                        ]);
                    }

                    ProductResource::createMove($productQuantity, $currentQuantity, $sourceLocationId, $destinationLocationId);
                }),
        ], parent::getHeaderActions());
    }
}
