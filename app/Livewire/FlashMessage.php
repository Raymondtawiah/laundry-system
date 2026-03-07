<?php

namespace App\Livewire;

use Livewire\Component;

class FlashMessage extends Component
{
    public $message = '';
    public $type = 'success';
    public $show = false;

    protected $listeners = ['flashMessage' => 'showMessage'];

    public function showMessage($message, $type = 'success')
    {
        $this->message = $message;
        $this->type = $type;
        $this->show = true;

        $this->dispatch('flash-message-shown');

        // Auto-hide after 3 seconds
        $this->dispatch('hide-flash-message')->self();
    }

    public function render()
    {
        return view('livewire.flash-message');
    }
}
