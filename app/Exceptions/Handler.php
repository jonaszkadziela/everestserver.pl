<?php

namespace App\Exceptions;

use App\View\Components\Notification;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\UnauthorizedException;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->renderable(function (UnauthorizedException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => $e->getMessage() ?: Lang::get('notifications.in-app.unauthorized.description'),
                ], Response::HTTP_UNAUTHORIZED);
            }

            Notification::push(
                Lang::get('notifications.in-app.unauthorized.title'),
                $e->getMessage() ?: Lang::get('notifications.in-app.unauthorized.description'),
                Notification::DANGER,
            );

            return redirect()->back();
        });
    }
}
