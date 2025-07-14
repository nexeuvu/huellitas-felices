<?php

namespace App\Livewire\Admin;

use App\Models\Species;
use Livewire\Component;

class SpeciesIndex extends Component
{
    public function render()
    {
        $species = Species::all(); // Cargar todas las especies
        return view('livewire.admin.species-index', compact('species'));
    }
}
