<?php

namespace App\Livewire\Admin;

use App\Models\Service;
use Livewire\Component;
use Livewire\WithPagination;

class ServiceTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public function render()
    {
        $services = Service::orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.service-table', compact('services'));
    }
}
