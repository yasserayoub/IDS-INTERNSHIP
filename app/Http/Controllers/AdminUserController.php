<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Role;

use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');

    }
    public function usermanagmentpage()
    {
        $users = User::all();
        return view('admin.users.usermanagment', compact('users'));
    }
    public function create()
    {
        $roles=Role::all();
        return view('admin.users.create', compact('roles'));
    }
   public function store(Request $request)
{
    $request->validate([
        'Name'       => 'required|string|max:255',
        'Email'      => 'required|email|unique:Users,Email',
        'Department' => 'required|string|max:255',
        'Password'   => 'required|string|min:8|confirmed',
        'RoleId'     => 'required|exists:Roles,Id',
        'IsActive'   => 'required|boolean',
    ]);

    $user = new User();

    $user->Name = $request->Name;
    $user->Email = $request->Email;
    $user->Password = bcrypt($request->Password);//we hash the pass
    $user->Department = $request->Department;
    $user->RoleId = $request->RoleId;
    $user->IsActive = $request->IsActive;

    $user->save();

    return redirect()->route('admin.users.create')
                     ->with('success', 'User created successfully.');
}

}
