<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BloodRequest;
use App\Models\Admin;
use App\Models\Staff;
use App\Models\User;
use App\Models\Donation;
use App\Models\BloodUnit;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller {

    public function index() {
        $admin = Auth::user();  // Current admin [web:18]
        
        // Define all variables FIRST (fixes #3)
        $admins = Admin::all();
        $staffs = Staff::all();
        $users = User::all();
        $donors = Donation::all();
        $requestors = BloodRequest::all();
        $bloodUnits = BloodUnit::all();
        
        // Now compact works (fixes #2: no key=>value)
        return view('admin.admindashboard', compact(
            'admin', 'admins', 'staffs', 'users', 
            'donors', 'requestors', 'bloodUnits'
        ));
    }

    public function showAdminLogin() {
    return view('auth.admin-login');
}

    public function adminLogin(Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

       if (Auth::attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();
        return redirect()->intended('/admindashboard');
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ])->onlyInput('email');
    }

    public function generatePdf() {
        // Complete data matching index() (fixes #5)
        $data = [
            'admins' => Admin::all(),
            'staffs' => Staff::all(),
            'users' => User::all(),
            'donors' => Donation::all(),
            'requestors' => BloodRequest::all(),
            'bloodUnits' => BloodUnit::all(),
        ];
        
        $pdf = Pdf::loadView('admin.pdf-report', $data);
        return $pdf->download('moro-general-report.pdf');
    }

    public function adminLogout(Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/admin-login');
}

}