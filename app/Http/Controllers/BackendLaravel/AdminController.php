<?php

namespace App\Http\Controllers\BackendLaravel;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:admin-user-list|admin-user-create|admin-user-edit|admin-user-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:admin-user-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:admin-user-edit', ['only' => ['show', 'update']]);
        $this->middleware('permission:admin-user-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $admins = Admin::get();
        $roles = Role::all();
        // $admin_role = Role::where()
        // $user = Auth::guard('admin')->user(); 
        return view('admin.pages.admin_user.create', compact('admins', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request->all();
        try {
            $user = Admin::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);
            
            $user->assignRole([$request->role_id]);
            return redirect()->back()->with('message', 'Successfully Added');
        } catch (Exception $e) {
            return redirect()->back()->with('message', 'Something Went Wrong!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $admin = Admin::findOrFail($id);
        $roles = Role::all();

        return view('admin.pages.admin_user.edit', compact('admin', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        // Admin
        $admin = Admin::find($id);

        // Validation Data
        $request->validate([
            'name' => 'required|max:50',
            'email' => 'required|max:100|email',
            'password' => 'nullable|min:6|confirmed',
        ]);

        if ($request->is_active == 'on') {
            $is_active = 1;
        } else {
            $is_active = 0;
        }

        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->is_active = $is_active;
        if ($request->password) {
            $admin->password = Hash::make($request->password);
        }
        $admin->update();

        $admin->roles()->detach();
        if ($request->role_id) {
            $admin->assignRole([$request->role_id]);
        }

        return redirect()->back()->with('message', 'Successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            Admin::find($id)->delete();
            return redirect()->route('admin.index')->with('message', 'Data Deleted Successfully');
        } catch (Exception $e) {
            return $e;
        }
    }
}
