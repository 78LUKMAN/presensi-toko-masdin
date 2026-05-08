<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Show admin login form.
     */
    public function showAdminLoginForm()
    {
        return view('admin.login');
    }

    /**
     * Handle admin login.
     */
    public function adminLogin(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials + ['role' => 'admin'], $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }

    /**
     * Show employee login form.
     */
    public function showEmployeeLoginForm()
    {
        return view('employee.login');
    }

    /**
     * Handle employee login.
     */
    public function employeeLogin(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials + ['role' => 'employee'], $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('employee.dashboard'));
        }

        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }

    /**
     * Log the user out.
     */
    public function logout(Request $request)
    {
        $role = Auth::user()?->role;

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($role === 'admin') {
            return redirect()->route('admin.login');
        }

        return redirect()->route('employee.login');
    }
}
