@extends('layouts.master')

@section('content')
    <h1>Create New Job</h1>
    <hr/>

    {!! Form::open(['url' => '/job', 'class' => 'form-horizontal', 'role' => 'form']) !!}
         @include('job._form')

        <!-- Submit Button -->
        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-5">
                {!! Form::submit('Create Job', ['class' => 'btn btn-primary']) !!}
            </div>
        </div>
    {!! Form::close() !!}
@endsection
