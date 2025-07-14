<?php

namespace App\Livewire\Admin;

use App\Models\Invoices;
use App\Models\Customer;
use Livewire\Component;
use Livewire\WithPagination;

class InvoicesTable extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.admin.invoices-table', [
            'invoices' => Invoices::with('customer')->latest()->paginate(10),
            'customers' => Customer::orderBy('apellidos')->get(), // necesario para el modal de edición
        ]);
    }
}
