<?php

namespace App\Livewire\Admin;

use App\Models\Species;
use Livewire\Component;
use Livewire\WithPagination;

class SpeciesTable extends Component
{
    use WithPagination;

    public function render()
    {
        $species = Species::orderBy('created_at', 'desc')->paginate(10);

        return view('livewire.admin.species-table', compact('species'));
    }
}
