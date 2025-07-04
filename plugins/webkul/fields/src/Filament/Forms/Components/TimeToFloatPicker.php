<?php

declare(strict_types=1);

namespace Webkul\Field\Filament\Forms\Components;

use Carbon\Carbon;
use Filament\Forms\Components\TimePicker;

class TimeToFloatPicker extends TimePicker
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->dehydrateStateUsing(function ($state): ?float {
            if (! $state) {
                return null;
            }

            // Convert time string to Carbon instance
            $time = Carbon::createFromFormat('H:i', $state);

            // Convert to float hours
            $hours = $time->format('H');
            $minutes = $time->format('i');

            return (float) $hours + ((float) $minutes / 60);
        });

        $this->afterStateHydrated(function ($state): ?string {
            if (! $state) {
                return null;
            }

            // Convert float back to time for display
            $hours = floor($state);
            $minutes = round(($state - $hours) * 60);

            return sprintf('%02d:%02d', $hours, $minutes);
        });
    }
}
