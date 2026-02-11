<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;

class Counter extends Component
{
    public $value = 0;
    public $duration = 2000; // Animation duration in milliseconds
    public $format = true; // Whether to format the number
    public $prefix = '';
    public $suffix = '';

    protected $listeners = ['refreshCounter' => 'refresh'];

    public function mount($value = 0, $duration = 2000, $format = true, $prefix = '', $suffix = '')
    {
        $this->value = $value;
        $this->duration = $duration;
        $this->format = $format;
        $this->prefix = $prefix;
        $this->suffix = $suffix;
    }

    public function refresh($newValue = null)
    {
        if ($newValue !== null) {
            $this->value = $newValue;
        }
    }

    public function render()
    {
        $formattedValue = $this->format ? number_format($this->value) : $this->value;

        return view('livewire.dashboard.counter', [
            'displayValue' => $this->prefix . $formattedValue . $this->suffix,
            'rawValue' => $this->value,
        ]);
    }
}
