<?php

use App\Livewire\IndexComponent;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(IndexComponent::class)
        ->assertStatus(200);
});
