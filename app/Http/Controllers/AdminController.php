<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BloodRequest;
use App\Models\User;
use App\Models\Donation;
use App\Models\BloodUnit;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller {

    public function index() {
        $admin = Auth::user();  // Current admin [web:18]
        
        // Define all variables FIRST (fixes #3)
        $admins = User::where('role', 'admin')->get();
        $staffs = User::where('role', 'staff')->get();
        $users = User::where('role', 'user')->get();
        $donors = Donation::all();
        $requestors = BloodRequest::all();
        $bloodUnits = BloodUnit::all();
        
        // Now compact works (fixes #2: no key=>value)
        return view('admin.admindashboard', compact(
            'admin', 'admins', 'staffs', 'users', 
            'donors', 'requestors', 'bloodUnits'
        ));
    }

    public function generatePdf() {
        // Complete data matching index() (fixes #5)
        $data = [
            'generated_at'        => now()->format('F d, Y h:i A'),
            'total_admins'        => User::where('role', 'admin')->count(),
            'total_users'         => User::where('role', 'user')->count(),
            'total_staff'         => User::where('role', 'staff')->count(),
            'total_donations'     => Donation::count(),
            'total_requests'      => BloodRequest::count(),
            'total_units'         => BloodUnit::count(),

            'recent_units'        => BloodUnit::with(['donation', 'request'])->latest()->take(10)->get(),
            'recent_donors'       => Donation::latest()->take(10)->get(),
        ];
        
        $pdf = Pdf::loadView('admin.pdf-report', $data)->setPaper('a4', 'portrait');
        return $pdf->download('moro-general-report ' . now()->format('Y-m-d') . ' .pdf');
    }

}