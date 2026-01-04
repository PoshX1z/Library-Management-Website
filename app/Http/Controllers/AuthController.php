<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Staff;

class AuthController extends Controller
{
    public function login()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $remember = $request->has('remember');

        $staff = Staff::where('email', $request->email)->first();

        if (!$staff) {
            return back()->withErrors([
                'email' => 'ไม่พบอีเมลนี้ในระบบ (Email not found)',
            ])->withInput();
        }

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            if (Auth::user()->role !== 'Super Admin') {
                Auth::logout();
                return back()->withErrors(['email' => 'ไม่มีสิทธิ์เข้าใช้งาน: เฉพาะ Super Admin เท่านั้น']);
            }

            return redirect()->intended('/');
        }

        return back()->withErrors([
            'password' => 'รหัสผ่านไม่ถูกต้อง (Incorrect Password)',
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}