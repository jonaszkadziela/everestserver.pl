<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
            'users' => (new UserController())->index(),
            default => [],
        };
    }
}
