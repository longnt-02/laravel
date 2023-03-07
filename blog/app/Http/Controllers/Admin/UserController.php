<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index() {
        $user = User::all();
        return view('admin.user.list', compact('user'));
    }

    public function create() {
        return view('admin.user.create');
    }

    public function store(Request $request) {

        $this->validate($request, 
        [
            'name' => 'required',
            'email' => 'required|unique:user|email',
            'password' => 'required|min:6|max:32',
            'confirm' => 'required|same:password',
            'is_admin' => 'required'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password), //Hash::make($request->password)
            'is_admin' => $request->is_admin
        ]);

        return redirect()->route('admin.user.index')->with('success', 'Create successfully');
    }

    public function edit($id) {
        
    }

    public function update(Request $request, $id) {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|unique:user|email',
            'password' => 'required|min:6|max:32',
            'confirm' => 'same:password'
        ]);
    }

    public function delete() {
        
    }
}
