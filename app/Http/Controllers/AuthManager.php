<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AuthManager extends Controller
{
    function login() {
        if (Auth::check()){
            return redirect(route('home'));
        }
        return view('login');
    }

    function signin() {
        if (Auth::check()){
            return redirect(route('home'));
        }
        return view('signin');
    }

    function loginPost(Request $request) {
        $request->validate([
            'email'=>'required',
            'password'=>'required',
        ]);

        $credentials = $request->only('email', 'password');

        if(Auth::attempt($credentials)) {
            return redirect()->intended(route('home'));
        }
        return redirect(route('login'))->with("error", "Login detiails are not valid");
    }

    function signinPost(Request $request) {
        $request->validate([
            'name'=>'required|unique:users',
            'email'=>'required|email|unique:users',
            'password'=>'required',
            'type'=>'required'
        ]);

        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['password'] = Hash::make($request->password);
        $data['type'] = $request->type;

        $user= User::create($data);

        if (!$user) {
            return redirect(route('signin'))->with("error", "Signin detiails are not valid");
        }
    
        Auth::login($user);
        return redirect()->route('home');
    }



    function logOut() {
        Session::flush();
        Auth::logout();
        return redirect(route('login'));
    }
}
