<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function index()
    {
        Log::info('User accessed the login page.');
        return view('login.index', [
            'title' => 'Login'
        ]);
    }

    public function authenticate(Request $request)
    {
        Log::info('Authentication attempt started.', ['username' => $request->username]);

        $credential = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        if (Auth::attempt($credential)) {
            Log::info('Authentication successful.', ['username' => $request->username]);
            $request->session()->regenerate();
            return redirect()->intended('/dashboard')->with('LoginBerhasil', 'Login Successful!');
        }

        Log::warning('Authentication failed.', ['username' => $request->username]);
        return back()->with('LoginError', 'Login Failed!');
    }

    public function logout(Request $request)
    {
        Log::info('User logged out.', ['username' => Auth::user()->username ?? 'Guest']);

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}