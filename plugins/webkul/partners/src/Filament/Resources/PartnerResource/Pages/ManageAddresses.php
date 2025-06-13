<?php

namespace Webkul\Partner\Filament\Resources\PartnerResource\Pages;

use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables\Table;
use Webkul\Partner\Filament\Resources\AddressResource;
use Webkul\Partner\Filament\Resources\PartnerResource;

class ManageAddresses extends ManageRelatedRecords
{
    protected static string $resource = PartnerResource::class;

    protected static string $relationship = 'addresses';

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    public static function getNavigationLabel(): string
    {
        return __('partners::filament/resources/partner/pages/manage-addresses.title');
    }

    public function form(Form $form): Form
    {
        return AddressResource::form($form);
    }

    public function table(Table $table): Table
    {
        return AddressResource::table($table);
    }
}
