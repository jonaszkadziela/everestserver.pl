<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    public ?string $title;
    public ?string $bodyClass;

    /**
     * Create a new component instance.
     */
    public function __construct(?string $title = null, ?string $bodyClass = null)
    {
        $this->title = $title;
        $this->bodyClass = $bodyClass;
    }

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.app');
    }
}
