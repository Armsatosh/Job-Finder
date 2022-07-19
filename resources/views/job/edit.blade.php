@extends('layouts.master')

@section('content')
    <h1>Edit Job</h1>
    <hr/>
    {!! Form::model($job, ['method' => 'put', 'route' => ['job.update', $job->id], 'class' => 'form-horizontal', 'role' => 'form']) !!}
    @include('job._form')


        <!-- Submit Button -->
        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-5">
                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
            </div>
        </div>
    {!! Form::close() !!}
@endsection
