<?php

declare(strict_types=1);

namespace Webkul\Sale\Livewire;

use Livewire\Attributes\Reactive;
use Livewire\Component;

final class Summary extends Component
{
    #[Reactive]
    public $products = [];

    public $subtotal = 0;

    public $totalDiscount = 0;

    public $totalTax = 0;

    public $grandTotal = 0;

    public $amountTax = 0;

    public $enableMargin = false;

    #[Reactive]
    public $currency;

    public function mount($currency, $products): void
    {
        $this->currency = $currency;

        $this->products = $products ?? [];
    }

    public function render()
    {
        return view('sales::livewire/summary', [
            'products' => $this->products,
        ]);
    }
}
