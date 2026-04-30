<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BloodUnit;
use App\Models\User;
use Illuminate\Http\Request;

class BloodUnitController extends Controller {

    public function store(Request $request) {
        $request->validate([
            'donation_id' => 'required|exists:donations,id',
            'blood_type'  => 'required|string|max:5',
            'request_id'  => 'required|exists:requests,id',
            'volume'      => 'required|integer|min:350|max:500',
            'expiry_date' => 'required|date|after:today',
        ]);

        BloodUnit::create($request->only('donation_id','blood_type','request_id','volume','expiry_date'));

        return back()->with('unit-created', 
            'Blood unit submitted successfully!');
    }

    public function update(Request $request, $id) {       // 👈 Add this method
        $request->validate([
            'donation_id' => 'required|exists:donations,id',
            'blood_type'  => 'required|string|max:5',
            'request_id'  => 'nullable|exists:requests,id',  // nullable since request_id can be '-'
            'volume'      => 'required|integer|min:350|max:500',
            'expiry_date' => 'required|date',                 // no after:today since existing units may already be near expiry
        ]);

        $unit = BloodUnit::findOrFail($id);
        $unit->update($request->only('donation_id','blood_type','request_id','volume','expiry_date'));

        return back()->with('unit-updated', 'Blood unit updated successfully!');
    }
}