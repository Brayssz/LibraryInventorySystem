<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AppController extends Controller
{
    public function showDashboard(Request $request)
    {
        return view('contents.dashboard');
    }
}
