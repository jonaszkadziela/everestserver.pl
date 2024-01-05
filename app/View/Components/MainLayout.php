<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class MainLayout extends Component
{
    public string $title;
    public string $bodyClass;
    public bool $withActionsMenu;
    public bool $withAnalytics;
    public bool $withFooter;

    /**
     * Create a new component instance.
     */
    public function __construct(string $title, string $bodyClass, bool $withActionsMenu, bool $withAnalytics, bool $withFooter)
    {
        $this->title = $title;
        $this->bodyClass = $bodyClass;
        $this->withActionsMenu = $withActionsMenu;
        $this->withAnalytics = $withAnalytics;
        $this->withFooter = $withFooter;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('layouts.main');
    }
}
