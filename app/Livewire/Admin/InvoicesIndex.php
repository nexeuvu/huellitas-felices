<?php

namespace App\Livewire\Admin;

use App\Models\Customer;
use Livewire\Component;

class InvoicesIndex extends Component
{
    public $customers;

    public function mount()
    {
        $this->customers = Customer::orderBy('apellidos')->get();
    }

    public function render()
    {
        return view('livewire.admin.invoices-index', [
            'customers' => $this->customers,
        ]);
    }
}
