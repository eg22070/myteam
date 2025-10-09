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
        $teams = Komanda::all();
        return view('auth.register', compact('teams'));
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
        'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
        'role' => ['required', 'string'],
    ]);
        // Validate Players table fields
        // Make sure these are actually submitted when role='player'
        $validatedPlayer = $request->validate([
            'team_id' => ['nullable', 'exists:komandas,id'],
            'player_number' => ['nullable', 'integer'],
            'birth_date' => ['nullable', 'date'],
        ]);
        $user = User::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        if ($user->role === 'Player') {
            \App\Models\Speletajs::create([
                'user_id' => $user->id,
                'komanda_id' => $validatedPlayer['team_id'] ?? null,
                'numurs' => $validatedPlayer['player_number'] ?? null,
                'dzimsanas_datums' => $validatedPlayer['birth_date'] ?? null,
            ]);
        }
        event(new Registered($user));


        return redirect()->route('dashboard')->with('success', 'User registered successfully.');
    }
}
