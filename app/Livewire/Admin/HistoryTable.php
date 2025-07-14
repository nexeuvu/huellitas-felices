<?php

namespace App\Livewire\Admin;

use App\Models\History;
use Livewire\Component;
use Livewire\WithPagination;

class HistoryTable extends Component
{
    use WithPagination;

    public function render()
    {
        $histories = History::with('pet.customer', 'veterinary.employee')->paginate(10);

        return view('livewire.admin.history-table', [
            'histories' => $histories,
        ]);
    }
}
