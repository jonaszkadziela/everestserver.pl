<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class UserSettingController extends Controller
{
    public function language(Request $request, string $code): RedirectResponse
    {
        $validated = $this->validate($request->merge(['code' => $code]), [
            'code' => 'required|in:' . join(',', config('app.languages')),
        ]);

        Session::put('language', $validated['code']);

        return redirect()->back();
    }

    public function theme(Request $request, string $mode): RedirectResponse
    {
        $validated = $this->validate($request->merge(['mode' => $mode]), [
            'mode' => 'required|in:dark,light',
        ]);

        Session::put('theme', $validated['mode']);

        return redirect()->back();
    }
}
