<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        return view('staff.staffdashboard');
    }

    public function admin() {
        if (Auth::user()->role !== 'admin') abort(403);
        return view('admin.admindashboard');
    }
}

