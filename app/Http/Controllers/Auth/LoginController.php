<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    public function show(Request $request) {
        return view("auth.login");
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            $school = School::where('email', operator: $request->email)->first();

            if (!$school || !Hash::check($request->password, $school->password)) {
                return back()->withErrors(['message' => 'Invalid credentials']);
            }
            
            session()->put('school_id', $school->school_id);
            session()->put('school_id_expires_at', now()->addMinutes(10));

            return redirect("/#available")->with('success', 'Login successful!');
        }

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(['message' => 'Invalid credentials']);
        }

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Login successful!');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        return redirect()->route('login')->with('success', 'Logout successful!');
    }
}
