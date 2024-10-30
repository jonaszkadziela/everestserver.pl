<?php

namespace App\View\Components;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class Navigation extends Component
{
    private array $navigation;
    private ?User $user;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->navigation = [
            'admin' => [
                'admin.panel' => route('admin.panel'),
            ],
            'common' => [
                'home' => route('home'),
                'dashboard' => route('dashboard'),
            ],
        ];

        $this->user = Auth::user();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('layouts.navigation');
    }

    /**
     * Return navigation links intended for admin users.
     */
    public function adminLinks(): array
    {
        return $this->navigation['admin'];
    }

    /**
     * Return navigation links intended for everyone.
     */
    public function commonLinks(): array
    {
        return $this->navigation['common'];
    }

    /**
     * Return navigation links available for the logged-in user.
     */
    public function links(): array
    {
        return [
            ...$this->commonLinks(),
            ...($this->user?->is_admin ? $this->adminLinks() : []),
        ];
    }
}
