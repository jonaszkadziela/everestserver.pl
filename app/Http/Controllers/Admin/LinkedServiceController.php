<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\IndexRequest;
use App\Http\Requests\Admin\LinkServiceRequest;
use App\Models\Service;
use App\Models\User;
use App\View\Components\Notification;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class LinkedServiceController extends Controller
{
    /**
     * Get all linked services.
     */
    public function index(IndexRequest $request): LengthAwarePaginator
    {
        return DB::table('services_users')
            ->select([
                DB::raw('CONCAT(services.name, " (#", service_id, ")") AS service'),
                DB::raw('CONCAT(users.username, " (#", user_id, ")") AS user'),
                'service_id',
                'user_id',
                'identifier',
            ])
            ->join('services', 'services.id', '=', 'services_users.service_id')
            ->join('users', 'users.id', '=', 'services_users.user_id')
            ->when(
                $request->search !== null,
                fn (Builder $query) => $query->where(
                    fn (Builder $query) => $query
                        ->where('service_id', 'LIKE', '%' . $request->search . '%')
                        ->orWhere('user_id', 'LIKE', '%' . $request->search . '%')
                        ->orWhere('identifier', 'LIKE', '%' . $request->search . '%')
                        ->orWhere('services.name', 'LIKE', '%' . $request->search . '%')
                        ->orWhere('users.username', 'LIKE', '%' . $request->search . '%')
                ),
            )
            ->when(
                $request->column !== null,
                fn (Builder $query) => $query->orderBy($request->column, $request->direction),
            )
            ->paginate(config('pagination.admin.linked_services'))
            ->withQueryString();
    }

    /**
     * Link service to a user.
     */
    public function link(LinkServiceRequest $request): RedirectResponse
    {
        try {
            $user = User::findOrFail($request->user_id);
            $service = Service::enabled()
                ->where('id', '=', $request->service_id)
                ->when(!$user?->is_admin, fn (EloquentBuilder $query) => $query->where('is_public', '=', true))
                ->firstOrFail();

            $linkedService = $user
                ->services()
                ->where('service_id', '=', $service->id)
                ->first();

            if ($linkedService !== null) {
                throw new HttpException(Response::HTTP_BAD_REQUEST, 'This service is already linked to the user');
            }

            $user->services()->save($service, ['identifier' => $request->identifier]);

            Notification::push(
                Lang::get('notifications.in-app.service-linked.title'),
                Lang::get('notifications.in-app.service-linked.description', ['service' => $service->name]),
                Notification::SUCCESS,
            );
        } catch (Throwable $t) {
            Notification::push(
                Lang::get('notifications.in-app.service-link-failed.title'),
                Lang::get('notifications.in-app.service-link-failed.description'),
                Notification::DANGER,
            );

            Log::info(class_basename($this) . ': ' . ($service?->name ?? 'The requested') . ' service could not be linked to user ' . $user->username, [
                'code' => $t->getCode(),
                'message' => $t->getMessage(),
                'file' => $t->getFile(),
                'line' => $t->getLine(),
            ]);
        }

        return redirect()->back();
    }

    /**
     * Unlink service from a user.
     */
    public function unlink(LinkServiceRequest $request): RedirectResponse
    {
        try {
            $user = User::findOrFail($request->user_id);
            $service = Service::findOrFail($request->service_id);

            $user->services()->detach($service);

            Notification::push(
                Lang::get('notifications.in-app.service-unlinked.title'),
                Lang::get('notifications.in-app.service-unlinked.description', ['service' => $service->name]),
                Notification::SUCCESS,
            );
        } catch (Throwable $t) {
            Notification::push(
                Lang::get('notifications.in-app.service-unlink-failed.title'),
                Lang::get('notifications.in-app.service-unlink-failed.description'),
                Notification::DANGER,
            );

            Log::info(class_basename($this) . ': ' . ($service?->name ?? 'The requested') . ' service could not be linked to user ' . $user->username, [
                'code' => $t->getCode(),
                'message' => $t->getMessage(),
                'file' => $t->getFile(),
                'line' => $t->getLine(),
            ]);
        }

        return redirect()->back();
    }
}
