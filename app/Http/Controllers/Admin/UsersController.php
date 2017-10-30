<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index(Request $request)
    {
        if (Auth::check() && Auth::user()->hasRole('admin') || Auth::user()->hasRole('Admin')) {
            $keyword = $request->get('search');
            $perPage = 15;

            if (!empty($keyword)) {
                $users = User::where('name', 'LIKE', "%$keyword%")->orWhere('email', 'LIKE', "%$keyword%")
                    ->paginate($perPage);
            } else {
                $users = User::paginate($perPage);
            }

            return view('admin.users.index', compact('users'));
        }else{
            return back();
        }


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        if (Auth::check() && Auth::user()->hasRole('admin') || Auth::user()->hasRole('Admin')) {
            $roles = Role::select('id', 'name', 'label')->get();
            $roles = $roles->pluck('label', 'name');

            return view('admin.users.create', compact('roles'));
        }else{
            return back();
        }


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function store(Request $request)
    {
        $this->validate($request, ['name' => 'required', 'email' => 'required', 'password' => 'required', 'roles' => 'required']);

        $data = $request->except('password');
        $data['password'] = bcrypt($request->password);
        $user = User::create($data);

        foreach ($request->roles as $role) {
            $user->assignRole($role);
        }

        Session::flash('flash_message', 'User added!');

        return redirect('admin/users');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function show($id)
    {
        if (Auth::check() && Auth::user()->hasRole('admin') || Auth::user()->hasRole('Admin')) {
            $user = User::findOrFail($id);

            return view('admin.users.show', compact('user'));
        }else{
            return back();
        }


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function edit($id)
    {
        if (Auth::check() && Auth::user()->hasRole('admin') || Auth::user()->hasRole('Admin')) {
            $roles = Role::select('id', 'name', 'label')->get();
            $roles = $roles->pluck('label', 'name');

            $user = User::with('roles')->select('id', 'name', 'email')->findOrFail($id);
            $user_roles = [];
            foreach ($user->roles as $role) {
                $user_roles[] = $role->name;
            }

            return view('admin.users.edit', compact('user', 'roles', 'user_roles'));
        }else{
            return back();
        }

    }
    /**
     * Show the form for editing the specified resource.
     *
     * @return void
     */
    public function editpassword()
    {
        if (Auth::check()){

            $user = Auth::user();
            return view('admin.users.password', compact('user'));

        }


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int      $id
     * @param  \Illuminate\Http\Request  $request
     *
     * @return void
     */
    public function update($id, Request $request)
    {

        if (Auth::check() && Auth::user()->hasRole('admin') || Auth::user()->hasRole('Admin')) {
            $this->validate($request, ['name' => 'required', 'email' => 'required', 'roles' => 'required']);

            $data = $request->except('password');
            if ($request->has('password')) {
                $data['password'] = bcrypt($request->password);
            }

            $user = User::findOrFail($id);
            $user->update($data);

            $user->roles()->detach();
            foreach ($request->roles as $role) {
                $user->assignRole($role);
            }

            Session::flash('flash_message', 'User updated!');

            return redirect('admin/users');
        }else{
            return back();
        }




    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return void
     */
    public function updatepassword(Request $request)
    {
        if(Auth::check()){

            $this->validate($request, ['password' => 'required|string|min:6|confirmed']);
            $password = bcrypt($request->password);
            $user = Auth::user();
            $user->password=$password;
            $user->save();
            Session::flash('flash_message', 'Mot de passe modifié');

            return  back();
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function destroy($id)
    {
        if (Auth::check() && Auth::user()->hasRole('admin') || Auth::user()->hasRole('Admin')) {
            User::destroy($id);

            Session::flash('flash_message', 'User deleted!');

            return redirect('admin/users');
        }else{
            return back();
        }
    }
}

