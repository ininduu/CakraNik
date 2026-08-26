<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        return view("profile.edit", compact("user"));
    }

    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $data = $request->validate([
            "name" => ["required", "string", "max:255"],
            "email" => ["required", "string", "email", "max:255", Rule::unique("users")->ignore($user->id)],
            "business_name" => ["nullable", "string", "max:255"],
            "phone" => ["nullable", "string", "max:20"],
            "address" => ["nullable", "string"],
            "city" => ["nullable", "string", "max:255"],
        ]);

        $user->update($data);

        return back()->with("success", "Profil berhasil diperbarui.");
    }
}