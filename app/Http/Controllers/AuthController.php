<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    // Show registration form
    public function showRegister()
    {
        return view('auth.userregister');
    }

    // Process registration
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'required|integer|min:18|max:65',
            'sex' => 'required|in:male,female',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|in:admin,staff,user',
        ]);

        User::create([
            'name' => $request->name,
            'age' => $request->age,
            'sex' => $request->sex,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect('/userlogin')->with('success', 'User registered successfully! Please login.');
    }


public function showLogin() {
    return view('auth.userlogin');
}

public function login(Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();
        $role = Auth::user()->role;

        return match ($role) {
            'admin' => redirect('/admindashboard'),
            'staff' => redirect('/staffdashboard'),
            'user' => redirect('/userdashboard'),
            default => redirect('/userlogin')->withErrors(['email' => 'Invalid role.']),
        };

    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ])->onlyInput('email');
}

public function logout(Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/userlogin');
}


}


