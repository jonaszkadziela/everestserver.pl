<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ServiceButton extends Component
{
    public string $icon;
    public string $link;
    public string $type;

    /**
     * Create a new component instance.
     */
    public function __construct(string $type, string $link)
    {
        $this->type = $type;
        $this->link = $link;

        $this->icon = match ($type) {
            'everestcloud' => 'fa-cloud fa-solid text-4xl',
            'everestpass' => 'fa-solid fa-unlock-keyhole text-4xl',
            'everestgit' => 'fa-brands fa-git-alt text-5xl',
            default => 'fa-server fa-solid text-4xl',
        };
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.service-button');
    }
}
