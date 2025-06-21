<?php

declare(strict_types=1);

namespace Webkul\Product\Filament\Resources\ProductResource\Pages;

use BackedEnum;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Webkul\Product\Filament\Resources\AttributeResource;
use Webkul\Product\Filament\Resources\ProductResource;
use Webkul\Product\Filament\Resources\ProductResource\Actions\GenerateVariantsAction;
use Webkul\Product\Models\ProductAttribute;

class ManageAttributes extends ManageRelatedRecords
{
    protected static string $resource = ProductResource::class;

    protected static string $relationship = 'attributes';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-swatch';

    public static function getSubNavigationPosition(): SubNavigationPosition
    {
        return SubNavigationPosition::Top;
    }

    public static function getNavigationLabel(): string
    {
        return __('products::filament/resources/product/pages/manage-attributes.title');
    }

    public function form(Form $form): Form
    {
        return $form
            ->components([
                Select::make('attribute_id')->label(__('products::filament/resources/product/pages/manage-attributes.form.attribute'))
                    ->required()
                    ->relationship(
                        'attribute',
                        'name',
                        modifyQueryUsing: fn (Builder $query) => $query->withTrashed(),
                    )
                    ->getOptionLabelFromRecordUsing(fn ($record): string => $record->name.($record->trashed() ? ' (Deleted)' : ''))
                    ->disableOptionWhen(fn (string $value) => $this->getOwnerRecord()->attributes->contains('attribute_id', $value))
                    ->searchable()
                    ->preload()
                    ->disabledOn('edit')
                    ->createOptionForm(fn (Schema $form): Schema => AttributeResource::form($form))
                    ->afterStateUpdated(function ($state, Set $set): void {
                        $set('options', []);
                    }),
                Select::make('options')->label(__('products::filament/resources/product/pages/manage-attributes.form.values'))
                    ->required()
                    ->relationship(
                        name: 'options',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn (Get $get, Builder $query) => $query->where('products_attribute_options.attribute_id', $get('attribute_id')),
                    )
                    ->searchable()
                    ->preload()
                    ->multiple(),
            ])
            ->columns(1);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->description(__('products::filament/resources/product/pages/manage-attributes.table.description'))
            ->columns([
                TextColumn::make('attribute.name')->label(__('products::filament/resources/product/pages/manage-attributes.table.columns.attribute')),
                TextColumn::make('values.attributeOption.name')->label(__('products::filament/resources/product/pages/manage-attributes.table.columns.values'))
                    ->badge(),
            ])
            ->headerActions([
                GenerateVariantsAction::make(),
                CreateAction::make()->label(__('products::filament/resources/product/pages/manage-attributes.table.header-actions.create.label'))
                    ->icon('heroicon-o-plus-circle')
                    ->mutateDataUsing(function (array $data): array {
                        $data['creator_id'] = Auth::id();

                        return $data;
                    })
                    ->after(function (ProductAttribute $record): void {
                        $this->updateOrCreateVariants($record);
                    })
                    ->successNotification(
                        Notification::make()->success()
                            ->title(__('products::filament/resources/product/pages/manage-attributes.table.header-actions.create.notification.title'))
                            ->body(__('products::filament/resources/product/pages/manage-attributes.table.header-actions.create.notification.body')),
                    ),
            ])
            ->recordActions([
                EditAction::make()->after(function (ProductAttribute $record): void {
                        $this->updateOrCreateVariants($record);
                    })
                    ->successNotification(
                        Notification::make()->success()
                            ->title(__('products::filament/resources/product/pages/manage-attributes.table.actions.edit.notification.title'))
                            ->body(__('products::filament/resources/product/pages/manage-attributes.table.actions.edit.notification.body')),
                    ),
                DeleteAction::make()->after(function (ProductAttribute $record): void {
                        $this->updateOrCreateVariants($record);
                    })
                    ->successNotification(
                        Notification::make()->success()
                            ->title(__('products::filament/resources/product/pages/manage-attributes.table.actions.delete.notification.title'))
                            ->body(__('products::filament/resources/product/pages/manage-attributes.table.actions.delete.notification.body')),
                    ),
            ])
            ->paginated(false);
    }

    private function updateOrCreateVariants(ProductAttribute $record): void
    {
        $record->values->each(function ($value) use ($record): void {
            $value->update([
                'extra_price' => $value->attributeOption->extra_price,
                'attribute_id' => $record->attribute_id,
                'product_id' => $record->product_id,
            ]);
        });

        $this->replaceMountedTableAction('products.generate.variants');
    }
}
