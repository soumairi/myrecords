@extends('layouts.backend')

@section('content')
    <div >
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Liste des sous projets</div>
                    <div class="panel-body">
                        <a href="{{ url('/admin/projects') }}" title="Back"><button class="btn btn-warning btn-xs"><i class="fa fa-arrow-left" aria-hidden="true"></i> Retour</button></a>
			@if(Auth::user()->can('projet-create'))
                        <a href="{{ url('/admin/under_-projects/create/'.$id) }}" class="btn btn-success btn-sm" title="Add New Under_Project">
                            <i class="fa fa-plus" aria-hidden="true"></i> Ajouter
                        </a>
			@endif
                        {!! Form::open(['method' => 'GET', 'url' => '/admin/under_-projects', 'class' => 'navbar-form navbar-right', 'role' => 'search'])  !!}
                        <input type="hidden" class="form-control" name="project"  value="{{$id}}">

                        <div class="input-group">
                            <input type="text" class="form-control" name="search" placeholder="Rechercher..." value="{{request('search')}}">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="submit">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                        </div>
                        {!! Form::close() !!}

                        <br/>
                        <br/>
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th>ID</th><th>Title</th><th>Description</th><th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($under_projects as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->title }}</td><td>{{ $item->description }}</td>
                                        <td>
                                            <a href="{{ url('/admin/under_-projects/' . $item->id) }}" title="View Under_Project"><button class="btn btn-info btn-xs"><i class="fa fa-eye" aria-hidden="true"></i> Détails</button></a>
                              		   @if(Auth::user()->can('projet-edit'))
                                            <a href="{{ url('/admin/under_-projects/' . $item->id . '/edit') }}" title="Edit Under_Project"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Modifier</button></a>
                                           @endif
					   @if(Auth::user()->can('projet-delete'))
                                            {!! Form::open([
                                                'method'=>'DELETE',
                                                'url' => ['/admin/under_-projects', $item->id],
                                                'style' => 'display:inline'
                                            ]) !!}
                                                {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> Supprimer', array(
                                                        'type' => 'submit',
                                                        'class' => 'btn btn-danger btn-xs',
                                                        'title' => 'Delete Under_Project',
                                                        'onclick'=>'return confirm("Confirmation de la suppression?")'
                                                )) !!}
                                            {!! Form::close() !!}
					  @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> {!! $under_projects->appends(['search' => Request::get('search')])->render() !!} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
