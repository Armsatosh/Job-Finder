@extends('layouts.master')

@section('content')
    <h1>Job's List <a href="{{ url('/job/create') }}" class="btn btn-primary pull-right btn-sm">Add New Job</a></h1>
    <hr/>

    @include('partials.flash_notification')

    @if(count($jobsList))
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Likes</th>
                    <th>Created</th>
                    <th>Updated</th>
                    <th>Action</th>
                </tr>
                </thead>

                <tbody>
                @foreach($jobsList as $job)
                    <tr>
                        <td>{{ $job->title }}</td>
                        <td>{{ $job->description }}</td>
                        <td>{{ $job->likes }}</td>
                        <td>{{ $job->created_at }}</td>
                        <td>{{ $job->updated_at }}</td>
                        <td>
                            {!! Form::open(['route' => ['job.edit', $job->id], 'class' => 'form-inline', 'method' => 'get']) !!}
                                    {!! Form::submit('Edit', ['class' => 'btn btn-success btn-xs']) !!}
                            {!! Form::close() !!}

                            {!! Form::open(['route' => ['job.destroy', $job->id], 'class' => 'form-inline', 'method' => 'delete']) !!}
                                {!! Form::hidden('id', $job->id) !!}
                                {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-xs']) !!}
                            {!! Form::close() !!}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="text-center">
            {!! $jobsList->onEachSide(2)->links('vendor.pagination.default') !!}
        </div>
    @else
        <div class="text-center">
            <h3>No jobs available yet</h3>
            <p><a href="{{ url('/job/create') }}">Create new job</a></p>
        </div>
    @endif

@endsection
