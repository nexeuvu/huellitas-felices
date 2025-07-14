<?php

namespace App\Livewire\Admin;

use App\Models\Breed;
use App\Models\Species;
use Livewire\Component;

class BreedIndex extends Component
{
    public $species = [];

    public function mount()
    {
        $this->species = Species::orderBy('nombre')->get();
    }

    public function render()
    {
        return view('livewire.admin.breed-index');
    }
}

