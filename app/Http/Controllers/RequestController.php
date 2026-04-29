<?php
namespace App\Http\Controllers;

use App\Models\BloodRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller {
    public function store(Request $request) {
        $request->validate([
            'requester_type' => 'required|in:hospital,patient',
            'patient_name' => 'required|string|max:255',
            'patient_age' => 'required|integer|min:0|max:120',
            'patient_sex' => 'required|in:male,female',
            'phone' => 'required|string|max:15',
            'email' => 'required|email',
            'address' => 'required|string|max:500',
            'required_blood_type' => 'required|in:A-,A+,B-,B+,AB-,AB+,O-,O+',
            'units' => 'required|integer|min:1|max:10',
            'urgency' => 'required|in:normal,emergency',
            'request_datetime' => 'required|date|after:now',
        ]);

        BloodRequest::create([
            'user_id' => Auth::id(),
            'requester_type' => $request->requester_type,
            'patient_name' => $request->patient_name,
            'patient_age' => $request->patient_age,
            'patient_sex' => $request->patient_sex,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'required_blood_type' => $request->required_blood_type,
            'units' => $request->units,
            'urgency' => $request->urgency,
            'request_datetime' => $request->request_datetime,
        ]);

        return back()->with('request-success', 
            'Blood request submitted! Admin will review and process it.');
    }
}

