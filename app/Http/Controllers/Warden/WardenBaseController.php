<?php

namespace App\Http\Controllers\Warden;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Hostel;

class WardenBaseController extends Controller
{
    protected $hostel;

    public function __construct()
    {
        // This is our manual security check, running for every warden page.
        if (!Auth::check() || Auth::user()->role !== 'warden') {
            // Abort the request and redirect to login if the user is not a warden
            return redirect()->route('login.form')
                ->with('error', 'You do not have permission to access this page.')
                ->send();
        }

        // Get the warden's assigned hostel and make it available to all child controllers
        $warden = Auth::user()->warden;
        $this->hostel = Hostel::where('warden_id', $warden->id)->first();
    }
}