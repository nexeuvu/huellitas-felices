<?php

namespace App\Livewire\Admin;

use App\Models\Employee;
use Livewire\Component;
use Livewire\WithPagination;

class EmployeeTable extends Component
{
    use WithPagination;

    public function render()
    {
        $employees = Employee::orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.employee-table', compact('employees'));
    }
}
