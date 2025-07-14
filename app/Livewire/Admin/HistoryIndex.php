<?php

namespace App\Livewire\Admin;

use App\Models\Pet;
use App\Models\Veterinary;
use App\Models\History;
use Livewire\Component;
use Livewire\WithPagination;

class HistoryIndex extends Component
{
    use WithPagination;

    public function render()
    {
        $pets = Pet::with('customer')->get();
        $veterinaries = Veterinary::with('employee')->get();
        $histories = History::with('pet.customer', 'veterinary.employee')->paginate(10);

        return view('livewire.admin.history-index', [
            'pets' => $pets,
            'veterinaries' => $veterinaries,
            'histories' => $histories,
        ]);
    }
}
