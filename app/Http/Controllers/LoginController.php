<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'max:255'],
            'password' => ['required'],
        ]);

        $user = User::firstOrCreate(
            ['email' => $credentials['email']],
            ['name' => $credentials['email'], 'password' => Str::random(32)]
        );

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended(route('tasks.index'));
    }

    public function registerForm()
    {
        return view('register');
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        Auth::login(User::create($data));
        $request->session()->regenerate();

        return redirect()->route('tasks.index');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
