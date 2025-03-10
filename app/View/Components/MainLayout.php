<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class MainLayout extends Component
{
    public Navigation $navigation;

    /**
     * Create a new component instance.
     */
    public function __construct(
        public ?string $title = null,
        public ?string $bodyClass = null,
        public ?string $header = null,
        public ?bool $withAnalytics = null,
        public ?bool $withActionsMenu = null,
        public ?bool $withNavigation = null,
        public ?bool $withFooter = null
    ) {
        $this->navigation = new Navigation();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('layouts.main');
    }
}
