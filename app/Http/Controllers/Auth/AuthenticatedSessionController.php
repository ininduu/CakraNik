<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// FR-01: login ke sistem
class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        return view("auth.login");
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            "email" => ["required", "email"],
            "password" => ["required"],
        ]);

        if (! Auth::attempt($credentials, $request->boolean("remember"))) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                "email" => "Email atau kata sandi salah.",
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route("dashboard"));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard("web")->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect("/");
    }
}