<?php

namespace App\Livewire\Admin;

use App\Models\Appointment;
use App\Models\Pet;
use App\Models\Veterinary;
use App\Models\Service;
use Livewire\Component;
use Livewire\WithPagination;

class AppointmentIndex extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.admin.appointment-index', [
            'appointments' => Appointment::with(['pet', 'veterinary', 'service'])->paginate(10),
            'pets' => Pet::all(),
            'veterinaries' => Veterinary::all(),
            'services' => Service::all(),
        ]);
    }
}
