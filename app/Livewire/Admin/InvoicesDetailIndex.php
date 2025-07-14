<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Invoices;
use App\Models\Service;
use App\Models\Product;

class InvoicesDetailIndex extends Component
{
    public $invoices;
    public $services;
    public $products;

    public function mount()
    {
        $this->invoices = Invoices::all();
        $this->services = Service::all();
        $this->products = Product::all();
    }

    public function render()
    {
        return view('livewire.admin.invoices-detail-index', [
            'invoices' => $this->invoices,
            'services' => $this->services,
            'products' => $this->products,
        ]);
    }
}
