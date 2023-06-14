<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\AuthEmail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    public function index()
    {
        return view('client.register');
    }
    public function store(Request $request)
    {
        // check email
        $user = User::where('email', $request->email)->first();
        if ($user) {
            return back()->with('error', 'Email đã được đăng ký');
        }
        $token = Str::random(40);
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:1',
            'password_confirmation' => 'required|same:password',
        ]);

        // create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'verification_token' => $token
        ]);

        // send email
        Mail::to('contact@example.com')->send(new AuthEmail($token));
        echo "Cảm ơn bạn đã đăng ký, bây giờ hãy vào email để xác thực";
    }
    public function verify($token)
    {
        // verify token
        $user = User::where('verification_token', $token)->first();
        if ($user) {
            $user->email_verified_at = now();
            $user->verification_token = null;
            $user->save();
            return redirect()->route('login')->with('success', 'Xác thực thành công');
        }
        abort(404);
    }
}
