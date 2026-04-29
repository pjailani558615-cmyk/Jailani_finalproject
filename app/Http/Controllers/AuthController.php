<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Admin;
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
        ]);

        User::create([
            'name' => $request->name,
            'age' => $request->age,
            'sex' => $request->sex,
            'email' => $request->email,
            'password' => Hash::make($request->password),  // Explicit hashing
        ]);

        return redirect('/userlogin')->with('success', 'Donor registered successfully! Please login.');
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
        return redirect()->intended('/userdashboard');
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

public function showStaffRegister() {
    return view('auth.staff-register');
}

public function showAdminRegister() {
    return view('auth.admin-register');
}


public function adminRegister(Request $request)
    {
        // 1) VALIDATE HERE
        $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'required|integer|min:21|max:65',
            'sex' => 'required|in:male,female',
            'email' => 'required|email|unique:admins',
            'password' => 'required|min:8|confirmed',
        ]);  // [web:46][web:49]

        // 2) CREATE ADMIN AFTER VALIDATION
        Admin::create([
            'name' => $request->name,
            'age' => $request->age,
            'sex' => $request->sex,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // 3) REDIRECT WITH SUCCESS MESSAGE
       return redirect('/admin-login')->with('success', 'Admin registered successfully! Please login.');
    }


}


