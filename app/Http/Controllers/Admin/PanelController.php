<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\IndexRequest;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Facades\Lang;
use Illuminate\View\View;

class PanelController extends Controller
{
    public const ALLOWED_TABS = [
        'services',
        'users',
    ];

    /**
     * Display the administration panel view.
     */
    public function index(IndexRequest $request): View
    {
        $activeTab = $request->tab ?? 'users';

        return view('admin.panel', [
            'activeTab' => $activeTab,
            'data' => $this->getData($request, $activeTab),
            'tabs' => self::ALLOWED_TABS,
        ]);
    }

    /**
     * Get data for the given tab.
     */
    private function getData(IndexRequest $request, string $tab): array
    {
        $raw = match ($tab) {
            'services' => (new ServiceController())->index($request),
            'users' => (new UserController())->index($request),
            default => [],
        };

        $transformed = match ($tab) {
            'services' => $raw
                ->getCollection()
                ->map(function (Service $service) {
                    return [
                        ...$service->getAttributes(),
                        'description' => $service->description,
                        'is_public' => $service->is_public ? Lang::get('main.yes') : Lang::get('main.no'),
                        'is_enabled' => $service->is_enabled ? Lang::get('main.yes') : Lang::get('main.no'),
                    ];
                })
                ->toArray(),
            'users' => $raw
                ->getCollection()
                ->map(function (User $user) {
                    return [
                        ...$user->getAttributes(),
                        'email_verified_at' => $user->email_verified_at?->toDateTimeString() ?? '-',
                        'is_admin' => $user->is_admin ? Lang::get('main.yes') : Lang::get('main.no'),
                        'is_enabled' => $user->is_enabled ? Lang::get('main.yes') : Lang::get('main.no'),
                    ];
                })
                ->toArray(),
            default => [],
        };

        return [
            'raw' => $raw,
            'transformed' => $transformed,
        ];
    }
}
