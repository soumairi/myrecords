<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Role;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Auth;

class RolesController extends Controller
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
                $roles = Role::where('name', 'LIKE', "%$keyword%")->orWhere('label', 'LIKE', "%$keyword%")
                    ->paginate($perPage);
            } else {
                $roles = Role::paginate($perPage);
            }

            return view('admin.roles.index', compact('roles'));
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
            return view('admin.roles.create');
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
        $this->validate($request, ['name' => 'required']);

        Role::create($request->all());

        Session::flash('flash_message', 'Role added!');

        return redirect('admin/roles');
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
            $role = Role::findOrFail($id);

            return view('admin.roles.show', compact('role'));
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
            $role = Role::findOrFail($id);

            return view('admin.roles.edit', compact('role'));
        }else{
            return back();
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
        $this->validate($request, ['name' => 'required']);

        $role = Role::findOrFail($id);
        $role->update($request->all());

        Session::flash('flash_message', 'Role updated!');

        return redirect('admin/roles');
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
            Role::destroy($id);

            Session::flash('flash_message', 'Role deleted!');

            return redirect('admin/roles');
        }else{
            return back();
        }
    }
}

