<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        // Get users for quick login panel
        $users = User::with('organization')->get();

        return view('auth.login', compact('users'));
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'))
                ->with('success', 'Welcome back, ' . Auth::user()->name . '!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }

    public function quickLogin($id)
    {
        $user = User::find($id);
        if ($user) {
            Auth::login($user);
            session()->regenerate();
            return redirect()->route('dashboard')
                ->with('success', 'Successfully logged in as ' . $user->name . ' (' . ucfirst($user->role) . ')');
        }
        return redirect()->route('login')->with('error', 'User not found.');
    }
}
