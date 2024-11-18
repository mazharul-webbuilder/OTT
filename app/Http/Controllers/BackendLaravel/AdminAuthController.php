<?php

namespace App\Http\Controllers\BackendLaravel;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AdminAuthController extends Controller
{
    public function loginPage()
    {
        return view('admin.pages.auth.login');
    }
    public function loginAction(Request $request)
    {
        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])) {
            if (Auth::guard('admin')->user()->is_active) {
                return redirect()->route('admin.dashboard')->with('message', 'Welcome To Admin Dashboard');
            } else {
                Auth::guard('admin')->logout();
                return redirect()->route('admin.login')->with('message', "Sorry you don't have login permission");
            }
        } else {
            return redirect()->back()->with('message', 'Something Went Wrong, login denied!!');
        }
    }
    public function registerPage()
    {
        return view('admin.pages.auth.register');
    }
    public function registerAction(Request $request)
    {
        // return $request->all();
        $rules = array(
            'email' => 'required|unique:admins',
            'password' => 'required',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ];
        try {
            Admin::create($data);
            return redirect()->route('admin.register')->with('message', 'Successfully Registered ,please wait for login approval');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something Went Wrong');
        }
    }
    public function logoutAdmin()
    {
        Session::flush();
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }

  
}
