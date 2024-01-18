<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Footer extends Component
{
    public array $links;
    public string $firstLink;
    public string $firstName;
    public string $lastLink;
    public string $lastName;

    /**
     * Create a new component instance.
     */
    public function __construct(string $encodedLinks)
    {
        $this->links = json_decode($encodedLinks, true);

        $this->firstName = array_key_first($this->links);
        $this->firstLink = array_shift($this->links);

        $this->lastName = array_key_last($this->links);
        $this->lastLink = array_pop($this->links);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.footer');
    }
}
