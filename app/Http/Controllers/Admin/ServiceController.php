<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateServiceRequest;
use App\Http\Requests\Admin\IndexRequest;
use App\Http\Requests\Admin\UpdateServiceRequest;
use App\Models\Service;
use App\View\Components\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Lang;

class ServiceController extends Controller
{
    /**
     * Get all services.
     */
    public function index(IndexRequest $request): LengthAwarePaginator
    {
        return Service::select([
            'id',
            'name',
            'description',
            'icon',
            'link',
            'is_public',
            'is_enabled',
        ])
        ->when(
            $request->search !== null,
            fn (Builder $query) => $query->where(
                fn (Builder $query) => $query
                    ->where('id', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('name', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('description', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('icon', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('link', 'LIKE', '%' . $request->search . '%')
            ),
        )
        ->when(
            $request->column !== null,
            fn (Builder $query) => $query->orderBy($request->column, $request->direction),
        )
        ->paginate(config('pagination.admin.services'));
    }

    /**
     * Create a new service.
     */
    public function store(CreateServiceRequest $request): RedirectResponse
    {
        $service = new Service();

        $service->setRawAttributes(['description' => $request->description]);

        $service->name = $request->name;
        $service->icon = $request->icon;
        $service->link = $request->link;
        $service->is_public = (bool)$request->is_public;
        $service->is_enabled = (bool)$request->is_enabled;

        $service->save();

        Notification::push(
            Lang::get('notifications.in-app.service-added.title'),
            Lang::get('notifications.in-app.service-added.description', ['service' => $service->name]),
            Notification::SUCCESS,
        );

        return redirect()->back();
    }

    /**
     * Update an existing service.
     */
    public function update(UpdateServiceRequest $request, Service $service): RedirectResponse
    {
        $service->setRawAttributes(['description' => $request->new_description]);

        $service->name = $request->new_name;
        $service->icon = $request->new_icon;
        $service->link = $request->new_link;
        $service->is_public = (bool)$request->new_is_public;
        $service->is_enabled = (bool)$request->new_is_enabled;

        $service->save();

        Notification::push(
            Lang::get('notifications.in-app.service-updated.title'),
            Lang::get('notifications.in-app.service-updated.description', ['service' => $service->name]),
            Notification::SUCCESS,
        );

        return redirect()->back();
    }

    /**
     * Delete an existing service.
     */
    public function destroy(Service $service): RedirectResponse
    {
        $service->delete();

        Notification::push(
            Lang::get('notifications.in-app.service-deleted.title'),
            Lang::get('notifications.in-app.service-deleted.description', ['service' => $service->name]),
            Notification::SUCCESS,
        );

        return redirect()->back();
    }
}
