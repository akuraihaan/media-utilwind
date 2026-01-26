<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /* ---------- VIEW ---------- */
    public function loginForm()    { return view('auth.login'); }
    public function registerForm() { return view('auth.register'); }
    public function forgotForm()   { return view('auth.forgot-password'); }
    public function resetForm($token, Request $r)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $r->email
        ]);
    }

    /* ---------- ACTION ---------- */
    public function login(Request $r)
    {
        $r->validate([
            'email'=>'required|email',
            'password'=>'required'
        ]);

        if(Auth::attempt($r->only('email','password'))) {
            $r->session()->regenerate();
            return redirect()->route('dashboard');
        }

        return back()->withErrors(['email'=>'Login gagal']);
    }

    public function register(Request $r)
    {
        $r->validate([
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:6|confirmed'
        ]);

        User::create([
            'name'=>$r->name,
            'email'=>$r->email,
            'password'=>Hash::make($r->password)
        ]);

        return redirect()->route('login')->with('success','Akun dibuat');
    }

    public function logout(Request $request)
{
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('landing');
}

    /* ---------- RESET PASSWORD ---------- */
    public function sendResetLink(Request $r)
    {
        $r->validate(['email'=>'required|email']);

        Password::sendResetLink($r->only('email'));

        return back()->with('status','Link reset dikirim ke email');
    }

    public function resetPassword(Request $r)
    {
        $r->validate([
            'token'=>'required',
            'email'=>'required|email',
            'password'=>'required|confirmed|min:6'
        ]);

        $status = Password::reset(
            $r->only('email','password','password_confirmation','token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->save();
            }
        );

        return $status == Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success','Password berhasil direset')
            : back()->withErrors(['email'=>'Token tidak valid']);
    }
}
