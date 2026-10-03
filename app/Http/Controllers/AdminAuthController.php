<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminAuthController extends Controller
{
    // Show the login form
    public function showLoginForm()
    {
        if (session('admin_authenticated')) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    // Process the submitted key
    public function login(Request $request)
    {
        $request->validate([
            'admin_key' => 'required|string',
        ]);
    
        // Check config first, then fallback to env variables
        $validKey = config('services.admin.key') 
                 ?? env('ADMIN_API_KEY') 
                 ?? env('ADMIN_KEY');
    
        if (!empty($validKey) && hash_equals($validKey, $request->input('admin_key'))) {
            // Mark session as authenticated
            session(['admin_authenticated' => true]);
    
            return redirect()->intended(route('admin.dashboard'));
        }
    
        return back()->withErrors([
            'admin_key' => 'Invalid admin key provided.',
        ]);
    }

    // Log out and clear session
    public function logout(Request $request)
    {
        $request->session()->forget('admin_authenticated');
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('status', 'You have been logged out.');
    }
}