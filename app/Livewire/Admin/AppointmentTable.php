<?php

namespace App\Livewire\Admin;

use App\Models\Appointment;
use Livewire\Component;
use Livewire\WithPagination;

class AppointmentTable extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.admin.appointment-table', [
            'appointments' => Appointment::with(['pet', 'veterinary', 'service'])->paginate(10),
        ]);
    }
}
