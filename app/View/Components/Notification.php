<?php

namespace App\View\Components;

use Illuminate\Support\Facades\Session;
use Illuminate\View\Component;
use Illuminate\View\View;

class Notification extends Component
{
    public const DANGER = 'danger';
    public const DEFAULT = 'default';
    public const INFO = 'info';
    public const SUCCESS = 'success';
    public const WARNING = 'warning';

    public string $borderClass;
    public string $iconClass;
    public string $textClass;

    /**
     * Create a new component instance.
     */
    public function __construct(
        public ?string $title,
        public ?string $body,
        public ?string $type = null,
    ) {
        $this->type ??= 'success';

        $this->borderClass = match ($this->type) {
            self::DANGER => 'border-red-400',
            self::INFO => 'border-blue-400',
            self::SUCCESS => 'border-green-500',
            self::WARNING => 'border-orange-400',
            default => 'border-gray-400',
        };

        $this->textClass = match ($this->type) {
            self::DANGER => 'text-red-400',
            self::INFO => 'text-blue-400',
            self::SUCCESS => 'text-green-500',
            self::WARNING => 'text-orange-400',
            default => 'text-gray-400',
        };

        $this->iconClass = match ($this->type) {
            self::DANGER => 'fa-solid fa-circle-exclamation',
            self::INFO => 'fa-solid fa-circle-info',
            self::SUCCESS => 'fa-solid fa-circle-check',
            self::WARNING => 'fa-solid fa-triangle-exclamation',
            default => 'fa-solid fa-bell',
        };
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('toasts.notification');
    }

    /**
     * Push notification to the session.
     */
    public static function push(string $title, string $body, string $type = self::DEFAULT): void
    {
        Session::push('notifications', [
            'title' => $title,
            'body' => $body,
            'type' => $type,
        ]);
    }
}
