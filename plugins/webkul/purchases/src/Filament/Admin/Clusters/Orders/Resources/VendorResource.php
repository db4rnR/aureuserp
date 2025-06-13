<?php

namespace Webkul\Purchase\Filament\Admin\Clusters\Orders\Resources;

use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Pages\Page;
use Filament\Resources\RelationManagers\RelationGroup;
use Webkul\Field\Filament\Traits\HasCustomFields;
use Webkul\Invoice\Filament\Clusters\Vendors\Resources\VendorResource as BaseVendorResource;
use Webkul\Partner\Filament\Resources\PartnerResource\RelationManagers;
use Webkul\Purchase\Filament\Admin\Clusters\Orders;
use Webkul\Purchase\Filament\Admin\Clusters\Orders\Resources\VendorResource\Pages;
use Webkul\Purchase\Models\Partner;

class VendorResource extends BaseVendorResource
{
    use HasCustomFields;

    protected static ?string $model = Partner::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static bool $shouldRegisterNavigation = true;

    protected static ?string $cluster = Orders::class;

    protected static ?int $navigationSort = 4;

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationLabel(): string
    {
        return __('purchases::filament/admin/clusters/orders/resources/vendor.navigation.title');
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\ViewVendor::class,
            Pages\EditVendor::class,
            Pages\ManageContacts::class,
            Pages\ManageAddresses::class,
            Pages\ManageBills::class,
            Pages\ManagePurchases::class,
        ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationGroup::make('Contacts', [
                RelationManagers\ContactsRelationManager::class,
            ])
                ->icon('heroicon-o-users'),

            RelationGroup::make('Addresses', [
                RelationManagers\AddressesRelationManager::class,
            ])
                ->icon('heroicon-o-map-pin'),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'     => Pages\ListVendors::route('/'),
            'create'    => Pages\CreateVendor::route('/create'),
            'view'      => Pages\ViewVendor::route('/{record}'),
            'edit'      => Pages\EditVendor::route('/{record}/edit'),
            'contacts'  => Pages\ManageContacts::route('/{record}/contacts'),
            'addresses' => Pages\ManageAddresses::route('/{record}/addresses'),
            'bills'     => Pages\ManageBills::route('/{record}/bills'),
            'purchases' => Pages\ManagePurchases::route('/{record}/purchases'),
        ];
    }
}
