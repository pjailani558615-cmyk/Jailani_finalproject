<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Donation;
use App\Models\BloodRequest;
use App\Models\BloodUnit;

class DashboardController extends Controller {
    public function index() {
        $user = auth()->user();
        $notifications = auth()->user()
                               ->notifications()
                               ->latest()
                               ->get();
        return view('userdashboard', compact('user', 'notifications'));
    }

    public function staff() {
        if (Auth::user()->role !== 'staff') abort(403);

        $donations = \App\Models\Donation::all();
        $requests = \App\Models\BloodRequest::all();
        $recent_notifications = \App\Models\Notification::latest()->take(10)->get();
        $blood_units = \App\Models\BloodUnit::all();

        $stats = [
            'total_donations' => $donations->count(),
            'total_requests' => $requests->count(),
        ];

        return view('staff.staffdashboard', compact('donations', 'requests', 'recent_notifications', 'blood_units', 'stats'));
    }

    public function admin() {
        if (Auth::user()->role !== 'admin') abort(403);

        $admins = User::where('role', 'admin')->get();
        $staffs = User::where('role', 'staff')->get();
        $users = User::where('role', 'user')->get();
        $donors = Donation::with('user')->get();
        $requestors = BloodRequest::with('user')->get();
        $bloodUnits = BloodUnit::all();

        return view('admin.admindashboard', compact('admins', 'staffs', 'users', 'donors', 'requestors', 'bloodUnits'));
    }
}

