<?php

declare(strict_types=1);

namespace App\Filament\Fabricator\PageBlocks;

use Filament\Forms\Components\Builder\Block;
use Z3d0X\FilamentFabricator\PageBlocks\PageBlock;

final class MyBlock extends PageBlock
{
    public static function getBlockSchema(): Block
    {
        return Block::make('my')
            ->schema([
                //
            ]);
    }

    public static function mutateData(array $data): array
    {
        return $data;
    }
}
