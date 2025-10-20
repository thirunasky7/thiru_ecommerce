<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // ✅ Use vendor authentication middleware
        $this->middleware('auth.vendor');
    }

    /**
     * Show the vendor dashboard.
     */
    public function index()
    {
        return view('vendor.dashboard'); // ✅ Ensure correct vendor view
    }
}
