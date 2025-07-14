<?php

namespace App\Livewire\Admin;

use App\Models\Veterinary;
use Livewire\Component;
use Livewire\WithPagination;

class VeterinaryTable extends Component
{
    use WithPagination;

    public function render()
    {
        $veterinaries = Veterinary::with('employee')->paginate(10);

        return view('livewire.admin.veterinary-table', compact('veterinaries'));
    }
}
