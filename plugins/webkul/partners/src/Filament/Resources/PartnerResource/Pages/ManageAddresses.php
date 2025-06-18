<?php

declare(strict_types=1);

namespace Webkul\Partner\Filament\Resources\PartnerResource\Pages;

use BackedEnum;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Webkul\Partner\Filament\Resources\AddressResource;
use Webkul\Partner\Filament\Resources\PartnerResource;

final class ManageAddresses extends ManageRelatedRecords
{
    protected static string $resource = PartnerResource::class;

    protected static string $relationship = 'addresses';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-map-pin';

    public static function getNavigationLabel(): string
    {
        return __('partners::filament/resources/partner/pages/manage-addresses.title');
    }

    public function form(Schema $schema): Schema
    {
        return AddressResource::form($schema);
    }

    public function table(Table $table): Table
    {
        return AddressResource::table($table);
    }
}
