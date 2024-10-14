<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PanelController extends Controller
{
    public const ALLOWED_TABS = [
        'users',
    ];

    /**
     * Display the administration panel view.
     *
     * @throws ValidationException
     */
    public function index(Request $request): View
    {
        $request->validate([
            'tab' => 'sometimes|required|in:' . implode(',', self::ALLOWED_TABS),
        ]);

        $activeTab = $request->tab ?? 'users';

        return view('admin.panel', [
            'activeTab' => $activeTab,
            'data' => $this->getData($activeTab),
            'tabs' => self::ALLOWED_TABS,
        ]);
    }

    /**
     * Get data for the given tab.
     */
    private function getData(string $tab): array
    {
        return match ($tab) {
            'users' => [
                'raw' => (new UserController())->index(),
                'transformed' => (new UserController())
                    ->index()
                    ->getCollection()
                    ->transform(function (User $user) {
                        return [
                            ...$user->getAttributes(),
                            'email_verified_at' => $user->email_verified_at?->toDateTimeString() ?? '-',
                            'is_admin' => $user->is_admin ? Lang::get('main.yes') : Lang::get('main.no'),
                            'is_enabled' => $user->is_enabled ? Lang::get('main.yes') : Lang::get('main.no'),
                        ];
                    })
                    ->toArray(),
            ],
            default => [],
        };
    }
}
