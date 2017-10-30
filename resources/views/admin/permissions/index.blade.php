@extends('layouts.backend')

@section('content')
    <div >
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Permissions</div>
                    <div class="panel-body">
                        <a href="{{ url('/admin/permissions/create') }}" class="btn btn-success btn-sm" title="Add New Permission">
                            <i class="fa fa-plus" aria-hidden="true"></i> Ajouter
                        </a>

                        {!! Form::open(['method' => 'GET', 'url' => '/admin/permissions', 'class' => 'navbar-form navbar-right', 'role' => 'search'])  !!}
                        <div class="input-group">
                            <input type="text" class="form-control" name="search" placeholder="Rechercher...">
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
                                        <th>ID</th><th>Name</th><th>Label</th><th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($permissions as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td><a href="{{ url('/admin/permissions', $item->id) }}">{{ $item->name }}</a></td><td>{{ $item->label }}</td>
                                        <td>
                                            <a href="{{ url('/admin/permissions/' . $item->id) }}" title="View Permission"><button class="btn btn-info btn-xs"><i class="fa fa-eye" aria-hidden="true"></i> Détails</button></a>
                                            <a href="{{ url('/admin/permissions/' . $item->id . '/edit') }}" title="Edit Permission"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Modifier</button></a>
                                            {!! Form::open([
                                                'method' => 'DELETE',
                                                'url' => ['/admin/permissions', $item->id],
                                                'style' => 'display:inline'
                                            ]) !!}
                                                {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> Supprimer', array(
                                                        'type' => 'submit',
                                                        'class' => 'btn btn-danger btn-xs',
                                                        'title' => 'Delete Permission',
                                                        'onclick'=>'return confirm("Confirmation de la suppression?")'
                                                )) !!}
                                            {!! Form::close() !!}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination"> {!! $permissions->appends(['search' => Request::get('search')])->render() !!} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
