@extends('layouts.backend')

@section('content')
    <div >
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Edit Under_Project #{{ $under_project->id }}</div>
                    <div class="panel-body">
                        <a href="{{ url('/admin/under_-projects') }}" title="Back"><button class="btn btn-warning btn-xs"><i class="fa fa-arrow-left" aria-hidden="true"></i> Retour</button></a>
                        <br />
                        <br />

                        @if ($errors->any())
                            <ul class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif

                        {!! Form::model($under_project, [
                            'method' => 'PATCH',
                            'url' => ['/admin/under_-projects', $under_project->id],
                            'class' => 'form-horizontal',
                            'files' => true
                        ]) !!}

                        {!! Form::hidden('project_id', null, ['class' => 'form-control']) !!}

                        @include ('admin.under_-projects.form', ['submitButtonText' => 'Update'])

                        {!! Form::close() !!}

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
