<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->isAdmin() || $user->role === 'analyst') {
                return redirect()->route('admin.dashboard');
            }

            if ($user->isCashier()) {
                return redirect()->route('cashier.dashboard');
            }

            return redirect()->route('customer.menu');
        }

        return response()
            ->view('auth.login')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => 'required|string',
            'password' => 'required',
        ]);

        $field = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $authCredentials = [
            $field => $credentials['login'],
            'password' => $credentials['password'],
        ];

        if (Auth::attempt($authCredentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();
            if ($user->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'));
            } elseif ($user->isCashier()) {
                return redirect()->intended(route('cashier.dashboard'));
            }

            if ($user->role === 'analyst') {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->intended(route('customer.menu'));
        }

        return back()->withErrors([
            'login' => 'Username/email atau password salah',
        ])->onlyInput('login');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
