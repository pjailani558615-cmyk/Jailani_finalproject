<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Donation;
use App\Models\BloodRequest;
use App\Models\BloodUnit;
use App\Models\Notification;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller {
    
    public function showStaffRegister() {
        return view('auth.staff-register');
    }

    public function staffregister(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'required|integer|min:21|max:65',
            'sex' => 'required|in:male,female',
            'email' => 'required|email|unique:staff,email',
            'password' => 'required|min:8|confirmed',
        ]); 

        $staff = Staff::create([
            'name' => $request->name,
            'age' => $request->age,
            'sex' => $request->sex,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect('/staff-login')->with('success', 'Staff account created!');
    }

    public function showStaffDashboard() {
        return view('staff.staffdashboard', [
        'staff' => Auth::user(),
        'stats' => [
            'total_donations' => Donation::count(),
            'pending_requests' => BloodRequest::where('status', 'pending')->count(),
            'available_units' => BloodUnit::where('status', 'available')->count(), // Add scope if needed
        ],
        'donations' => Donation::latest()->take(50)->get(),  // Paginate for prod
        'requests' => BloodRequest::latest()->take(50)->get(),
        'donors' => User::whereHas('donations')->get(),
        'recent_notifications' => collect([]),  // Or Notification::latest()->take(10)->get()
        'blood_units' => BloodUnit::with(['donation.user', 'request'])->latest()->take(50)->get(),
    ]);
    }

    public function staffdashboard(Request $request) {
        $staff = auth()->user();
        
        return view('staff.dashboard', [
            'staff' => $staff,
            'stats' => [
                'total_donations' => Donation::count(),
                'pending_requests' => BloodRequest::where('status', 'pending')->count(),
                'available_units' => BloodUnit::available()->count(),
                'expiring_units' => BloodUnit::expiringSoon()->count(),
            ],
            'donations' => Donation::latest()->take(20)->get(),
            'requests' => BloodRequest::latest()->take(20)->get(),
            'recent_notifications' => Notification::recent()->get(),
            'donors' => Donation::select('id', 'fullname', 'bloodtype')->distinct()->take(50)->get(),
            'blood_units' => BloodUnit::with(['donation', 'request'])->latest()->take(25)->get(),
        ]);
    }

    public function sendNotification(Request $request) {
      /*  $request->validate([
            'receiver_email' => 'required|email',
            'message' => 'required|string|max:2000',
        ]); */

        Notification::create([
            'staff_id' => auth()->id(),
            'receiver_email' => $request->receiver_email,
            'message' => $request->message,
            'status' => 'sent',  // Simulate sent
        ]);

        // TODO: Integrate Mail::send()

        return back()->with('notification-sent', 'Notification sent successfully!');
    }

    public function storeBloodUnit(Request $request) {
      /*  $request->validate([
            'donor_id' => 'required|exists:donations,id',
            'blood_type' => 'required|in:A-,A+,B-,B+,AB-,AB+,O-,O+',
            'volume' => 'required|numeric|min:350|max:500',
            'collection_date' => 'required|date|before:today',
            'expiry_date' => 'required|date|after:collection_date',
            'location' => 'required|string|max:50',
        ]); */

        BloodUnit::create([
            'unit_code' => $unitCode,
            'donation_id' => $request->donor_id,
            'blood_type' => $request->blood_type,
            'volume' => $request->volume,
            'collection_date' => $request->collection_date,
            'expiry_date' => $request->expiry_date,
            'location' => $request->location,
            'staff_id' => auth()->id(),
        ]);

        return back()->with('unit-created', "Unit {$unitCode} registered successfully!");
    }

    public function showStaffLogin() {
    return view('auth.staff-login');
}

public function stafflogin(Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();
        return redirect()->intended('/staffdashboard');
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ])->onlyInput('email');
}

public function stafflogout(Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/staff-login');
}

}
