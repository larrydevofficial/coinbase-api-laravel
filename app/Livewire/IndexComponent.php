<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

class IndexComponent extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.index-component');
    }
}
