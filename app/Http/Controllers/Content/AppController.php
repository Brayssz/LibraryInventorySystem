<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AppController extends Controller
{
    public function showRequestForm (Request $request) {
        if (session()->has('school_id_expires_at') && now()->lessThan(session('school_id_expires_at'))) {
            $school_id = session('school_id');

            return view('contents.request-form',compact('school_id'));
        } else {
            session()->forget('school_id');
            session()->forget('school_id_expires_at');
            
            return redirect('/login')->withErrors('Session expired, please log in again.');
        }
        
    }
    public function showDashboard(Request $request)
    {
        return view('contents.dashboard');
    }
}
