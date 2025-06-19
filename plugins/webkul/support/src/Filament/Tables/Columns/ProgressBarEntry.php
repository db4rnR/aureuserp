<?php

declare(strict_types=1);

namespace Webkul\Support\Filament\Tables\Columns;

use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\Concerns\HasColor;

class ProgressBarEntry extends Column
{
    use HasColor {
        getColor as getBaseColor;
    }

    protected string $view = 'support::tables.columns.progress-bar-entry';

    private $canShow = true;

    public function hideProgressValue($canShow = false): self
    {
        $this->canShow = $canShow;

        return $this;
    }

    public function getCanShow(): bool
    {
        return $this->canShow;
    }
}
