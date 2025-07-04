<?php

declare(strict_types=1);

namespace Webkul\Account\Livewire;

use Livewire\Attributes\Reactive;
use Livewire\Component;

class InvoiceSummary extends Component
{
    #[Reactive]
    public $products = [];

    public $subtotal = 0;

    public $totalDiscount = 0;

    public $totalTax = 0;

    public $grandTotal = 0;

    public $amountTax = 0;

    #[Reactive]
    public $currency;

    public function mount($currency, $products): void
    {
        $this->currency = $currency;

        $this->products = $products ?? [];
    }

    public function render()
    {
        return view('accounts::livewire/invoice-summary', [
            'products' => $this->products,
        ]);
    }
}
