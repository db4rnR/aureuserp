<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Webkul\Purchase\Livewire\RespondQuotation;

Route::middleware(['web'])->group(function (): void {
    Route::middleware('signed')
        ->get('purchase/{order}/{action}', RespondQuotation::class)
        ->name('purchases.quotations.respond');
});
