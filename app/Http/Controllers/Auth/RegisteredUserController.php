<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

// FR-01: registrasi akun, pilih jenis pengguna (Supply/Demand)
class RegisteredUserController extends Controller
{
    public function create()
    {
        return view("auth.register");
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            "name" => ["required", "string", "max:255"],
            "role" => ["required", "in:supply,demand"],
            "business_name" => ["nullable", "string", "max:255"],
            "phone" => ["nullable", "string", "max:30"],
            "city" => ["nullable", "string", "max:100"],
            "email" => ["required", "string", "email", "max:255", "unique:" . User::class],
            "password" => ["required", "confirmed", Rules\Password::defaults()],
        ]);

        $user = User::create([
            "name" => $request->name,
            "role" => $request->role,
            "business_name" => $request->business_name,
            "phone" => $request->phone,
            "city" => $request->city,
            "email" => $request->email,
            "password" => Hash::make($request->password),
        ]);

        event(new Registered($user));
        Auth::login($user);

        return redirect(route("dashboard"));
    }
}