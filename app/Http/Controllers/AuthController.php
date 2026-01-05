<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'role' => 'nullable|in:admin,technician,trainer'
        ]);

        $role = $credentials['role'] ?? null;
        unset($credentials['role']);

        // Try admin login
        if (!$role || $role === 'admin') {
            if (Auth::guard('web')->attempt($credentials, $request->filled('remember'))) {
                $request->session()->regenerate();
                return redirect('/admin/panel');
            }
        }

        // Try technician login
        if (!$role || $role === 'technician') {
            if (Auth::guard('technician')->attempt($credentials, $request->filled('remember'))) {
                $request->session()->regenerate();
                return redirect('/technician/panel');
            }
        }

        // Try trainer login
        if (!$role || $role === 'trainer') {
            if (Auth::guard('trainer')->attempt($credentials, $request->filled('remember'))) {
                $request->session()->regenerate();
                return redirect('/trainer/panel');
            }
        }

        return back()->withErrors(['email' => 'Invalid credentials'])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        // Logout from all guards
        Auth::guard('web')->logout();
        Auth::guard('technician')->logout();
        Auth::guard('trainer')->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function user()
    {
        return response()->json(Auth::user());
    }

    public function showForgotPassword()
    {
        return view('forgot-password');
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email', 'role' => 'required|in:admin,technician,trainer']);

        $status = Password::broker('users')->sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    public function showResetPassword($token)
    {
        return view('reset-password', ['token' => $token, 'email' => request('email')]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
