<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Komanda;
use App\Providers\RouteServiceProvider;
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
    public function showRegistrationForm()
    {
        return view('auth.register'); // Return the registration view
    }
    public function store(Request $request): RedirectResponse
    {
        // Validate Users table fields
        $validatedUser = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'surname' => ['required', 'string', 'max:255'],
        'birth_date' => ['required', 'date'],
        'bilde' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
        'role' => ['required', 'string'],
        ]);
        $photoPath = null;
        if ($request->hasFile('bilde')) {
            $photoPath = $request->file('bilde')->store('photos', 'public');  // Store in storage/app/public/photos directory
        }
        $user = User::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'dzimsanas_datums' => $request->birth_date,
            'bilde' => $photoPath,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);
        

        event(new Registered($user));

        return redirect()->route('dashboard')->with('success', 'User registered successfully.');
    }
}
