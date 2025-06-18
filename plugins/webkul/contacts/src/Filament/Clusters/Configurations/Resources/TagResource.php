<?php

declare(strict_types=1);

namespace Webkul\Contact\Filament\Clusters\Configurations\Resources;

use BackedEnum;
use Webkul\Contact\Filament\Clusters\Configurations;
use Webkul\Contact\Filament\Clusters\Configurations\Resources\TagResource\Pages\ManageTags;
use Webkul\Partner\Filament\Resources\TagResource as BaseTagResource;

final class TagResource extends BaseTagResource
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-tag';

    protected static bool $shouldRegisterNavigation = true;

    protected static ?int $navigationSort = 1;

    protected static ?string $cluster = Configurations::class;

    public static function getNavigationLabel(): string
    {
        return __('contacts::filament/clusters/configurations/resources/tag.navigation.title');
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageTags::route('/'),
        ];
    }
}
