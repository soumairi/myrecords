<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Project;
use App\Role;
use App\Under_Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Session;

class ProjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        if(Auth::check()){
            $roles = Auth::user()->roles;
          //  dd($roles);
            $list_roles = array();
            foreach ($roles as $r) {
                $list_roles[] = $r->id;
            }
            //dd($list_roles);
            $keyword = $request->get('search');
            $perPage = 25;

            if (!empty($keyword)) {
                $projects = DB::table('projects')
                    ->join('project_role', 'projects.id', '=', 'project_role.project_id')
                    ->whereIn('project_role.role_id', $list_roles)
                    ->where('title', 'LIKE', "%$keyword%")
                    ->orWhere('description', 'LIKE', "%$keyword%")
                    ->paginate($perPage);
            } else {
                $projects = DB::table('projects')
                    ->join('project_role', 'projects.id', '=', 'project_role.project_id')
                    ->whereIn('project_role.role_id', $list_roles)
                    ->paginate($perPage);
            }

            return view('admin.projects.index', compact('projects'));
        }else{
            return view('auth.login');
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        if(Auth::check() && Auth::user()->can('projet-create')) {
            return view('admin.projects.create');
        }else{
            return back();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        if(Auth::check() && Auth::user()->can('projet-create')) {
            $this->validate($request, [
                'title' => 'required'
            ]);
            $requestData = $request->all();

            $projet = Project::create($requestData);
            //dd($projet);
            $role = Role::create(array('name' => $projet->title . '_' . $projet->id, 'label' => $projet->title));
            $roleadmin = Role::where('name', 'like', 'admin')->get();
            //dd($roleadmin->id);
            $projet->roles()->attach($roleadmin);
            if (sizeof($role)) {

                $projet->roles()->attach($role->id);

            }

            Session::flash('flash_message', 'Projet créé avec succes!');

            return redirect('admin/projects');
        }else{
            return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        if(Auth::check() && Auth::user()->can('projet-list')) { //$project = Project::findOrFail($id);
            $roles = Auth::user()->roles;
            //  dd($roles);
            $list_roles = array();
            foreach ($roles as $r) {
                $list_roles[] = $r->id;
            }
            //dd($list_roles);
            $under_projects = DB::table('under__projects')
                ->join('under_project_role', 'under__projects.id', '=', 'under_project_role.under__project_id')
                ->whereIn('under_project_role.role_id', $list_roles)
                ->where('project_id', '=', $id)
                ->paginate(40);

            return view('admin.under_-projects.index', compact('under_projects','id'));


        }else{
            return back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        if(Auth::check() && Auth::user()->can('projet-edit')) {
            $project = Project::findOrFail($id);

            return view('admin.projects.edit', compact('project'));
        }else{
            return back();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {
        $this->validate($request, [
			'title' => 'required'
		]);
        $requestData = $request->all();
        
        $project = Project::findOrFail($id);
        $project->update($requestData);

        Session::flash('flash_message', 'Projet modifié avec succes!');

        return redirect('admin/projects');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        if(Auth::check() && Auth::user()->can('projet-delete')) {
        Project::destroy($id);

        Session::flash('flash_message', 'Projet supprimé avec succes!');

        return redirect('admin/projects');}
        else{
            return Back();
        }
    }
}
