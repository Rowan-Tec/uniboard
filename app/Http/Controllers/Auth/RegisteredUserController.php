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
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
{
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
        'id_number' => ['required', 'string', 'max:20', 'unique:users,id_number'],
        'phone' => ['nullable', 'string', 'max:15'],
        'department' => ['required', 'string', 'max:100'],
        'gender' => ['required', 'in:male,female,other'],
        'year' => ['required', 'string', 'max:10'],
        'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
    ]);

    $data = $request->only([
        'name', 'email', 'id_number', 'phone', 'department', 'gender', 'year'
    ]);
    $data['password'] = Hash::make($request->password);

    // Handle profile photo upload
    if ($request->hasFile('profile_photo')) {
        $path = $request->file('profile_photo')->store('profile_photos', 'public');
        $data['profile_photo_path'] = $path;
    }

    $user = User::create($data);

    event(new Registered($user));
    Auth::login($user);

    return redirect(route('dashboard', absolute: false));
}
}