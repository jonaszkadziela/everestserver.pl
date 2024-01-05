<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function change(Request $request, string $code): RedirectResponse
    {
        $validated = $this->validate($request->merge(['code' => $code]), [
            'code' => 'required|in:' . join(',', config('app.languages')),
        ]);

        Session::put('language', $validated['code']);

        return redirect()->back();
    }
}
