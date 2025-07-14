<?php

namespace App\Livewire\Admin;

use App\Models\Breed;
use App\Models\Species;
use Livewire\Component;
use Livewire\WithPagination;

class BreedTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public function render()
    {
        $breeds = Breed::with('species')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $species = Species::orderBy('nombre')->get();

        return view('livewire.admin.breed-table', compact('breeds', 'species'));
    }
}
