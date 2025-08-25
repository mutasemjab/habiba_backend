<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

class UserController extends Controller
{
    function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }
    function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }
    // public function store(Request $request)
    // {
    //     $this->validate($request, [
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|email|unique:users,email',
    //         'password' => 'required|confirmed',
    //         'roles' => 'required'
    //     ]);
    //     $input = $request->except(['password_confirmation', 'roles']);
    //     $input['password'] = Hash::make($input['password']);
    //     $user = User::create($input);
    //     $user->assignRole($request->input('roles'));
    //     return redirect(route('users.index'))->with('success', 'User created successfully.');
    // }
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'mobile' => 'required|unique:users,mobile',
            'password' => 'required|confirmed',
            'roles' => 'required'
        ]);
        $input = $request->except(['password_confirmation', 'roles']);
        $input['password'] = Hash::make($input['password']);

        $user = User::create($input);
        $user->assignRole($request->input('roles'));

        return redirect()->route('users.index')
            ->with('success', 'User created successfully');
    }

    function edit($id)
    {
        $user = User::find($id);
        $roles = Role::pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();

        return view('users.edit', compact('user', 'roles', 'userRole'));
    }
    // public function update(Request $request, $id)
    // {
    //     $user = User::findOrFail($id);
    //     $this->validate($request, [
    //         'name' => 'required',
    //         'email' => 'required|email|unique:users,email,' . $id,
    //         'mobile' => 'required|unique:users,mobile,' . $id,
    //         'password' => 'nullable|confirmed',
    //         'status' => 'required|in:0,1',
    //     ]);
    //     $userData = $request->except('password_confirmation', 'password');
    //     if ($request->filled('password')) {
    //         $userData['password'] = Hash::make($request->password);
    //     }
    //     $user->update($userData);
    //     return redirect(route('users.index'));
    // }
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'mobile' => 'required|unique:users,mobile,'.$id,
            'password' => 'nullable|confirmed',
            'roles' => 'required'
        ]);

        $input = $request->except(['password_confirmation', 'roles']);
        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = Arr::except($input, array('password'));
        }

        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id', $id)->delete();

        $user->assignRole($request->input('roles'));

        return redirect()->route('users.index')
            ->with('success', __('messages.user_update_successful'));
    }

    function activate($id)
    {
        $user = User::find($id);
        $user->update([
            'status' => 1
        ]);
        return redirect(route('users.index'))->with('success', __('messages.user_update_successful'));
    }
    function disable($id)
    {
        $user = User::find($id);
        $user->update([
            'status' => 0
        ]);
        return redirect(route('users.index'))->with('success', __('messages.user_update_successful'));
    }
    function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
        return redirect(route('users.index'))->with('success', __('messages.user_delete_successful'));
    }
}