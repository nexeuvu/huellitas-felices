<?php

namespace App\Livewire\Admin;

use App\Models\Customer;
use Livewire\Component;
use Livewire\WithPagination;

class CustomerTable extends Component
{
    use WithPagination;

    public function render()
    {
        // Elimina el where('status', 1) si no existe la columna
        $customers = Customer::paginate(10);
        
        return view('livewire.admin.customer-table', [
            'customers' => $customers
        ]);
    }
}
