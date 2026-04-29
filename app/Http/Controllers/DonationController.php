<?php
namespace App\Http\Controllers;

use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DonationController extends Controller {
   public function store(Request $request) {
      /*  $request->validate([
            'fullname' => 'required|string|max:255',
            'sex' => 'required|in:male,female',
            'age' => 'required|integer|min:18|max:65',
            'phone' => 'required|string|max:15',
            'email' => 'required|email',
            'address' => 'required|string|max:500',
            'bloodtype' => 'required|in:A-,A+,B-,B+,AB-,AB+,O-,O+',
            'weight' => 'required|numeric|min:50',
            'disease' => 'sometimes|boolean',
        ]); */

        Donation::create([
            'user_id' => Auth::id(),
            'fullname' => $request->fullname,
            'sex' => $request->sex,
            'age' => $request->age,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'bloodtype' => $request->bloodtype,
            'weight' => $request->weight,
            'dateoflastdonation' => $request->dateoflastdonation,
            'disease' => $request->boolean('disease'),
        ]);

       return back()->with('donation-success', 
            'Donation request submitted successfully! Staff will contact you soon.');
    }
}

