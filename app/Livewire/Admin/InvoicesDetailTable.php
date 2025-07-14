<?php

namespace App\Livewire\Admin;

use App\Models\Invoices_detail;
use Livewire\Component;
use Livewire\WithPagination;

class InvoicesDetailTable extends Component
{
    use WithPagination;

    public function render()
    {
        $details = Invoices_detail::with(['invoice', 'service', 'product'])->paginate(10);

        return view('livewire.admin.invoices-detail-table', [
            'details' => $details
        ]);
    }
}
