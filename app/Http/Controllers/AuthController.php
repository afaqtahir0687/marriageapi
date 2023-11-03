<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use DB;
use Session;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function index()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {

        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        $user = Admin::where('email', $request->email)->first();
        if ($user && Hash::check($request->password, $user->password)) 
            {
                Session::put(['user'=>$user]);
                return redirect('/')->withSuccess('Login Access');
            }
        return redirect("login")->withSuccess('Login details are not valid');
    }
    public function show_profile()
    {
        return view('admin.profile.index');
    }
    public function update_password(Request $request)
    {
        $this->validate($request, [
            'current_password' => 'required|string',
            'new_password' => 'required|confirmed|min:3|string'
        ]);
        $login_user = $request->session()->get('user');
        $user =  Admin::find($login_user->id);
        // The passwords matches
        if (!Hash::check($request->get('current_password'), $user->password)) 
        {
            return back()->with('error', "Current Password is Invalid");
        }
        // Current password and new password same
        if (strcmp($request->get('current_password'), $request->new_password) == 0) 
        {
            return redirect()->back()->with("error", "New Password cannot be same as your current password.");
        }
        $user->password =  Hash::make($request->new_password);
        $user->save();
        return back()->with('success', "Password Changed Successfully");
    }
    public function logout()
    {  
        session()->forget('user');
        return Redirect('login');
    }   
}
