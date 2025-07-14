<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public function render()
    {
        $products = Product::orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.product-table', compact('products'));
    }
}
