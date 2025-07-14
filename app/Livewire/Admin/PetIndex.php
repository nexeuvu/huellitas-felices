<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Customer;
use App\Models\Breed; // Asegúrate de importar el modelo de razas

class PetIndex extends Component
{
    public $customers;
    public $breeds; // Nueva propiedad para las razas
    
    public function mount()
    {
        $this->customers = Customer::all();
        $this->breeds = Breed::all(); // Cargar todas las razas
    }
    
    public function render()
    {
        return view('livewire.admin.pet-index', [
            'customers' => $this->customers,
            'breeds' => $this->breeds // Pasar las razas a la vista
        ]);
    }
}