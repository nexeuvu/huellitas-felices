<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Pet;

class PetTable extends Component
{
    use WithPagination;

    public function render()
    {
        $pets = Pet::with(['customer', 'breed'])->paginate(10); // relaciones necesarias

        return view('livewire.admin.pet-table', [
            'pets' => $pets
        ]);
    }
}
