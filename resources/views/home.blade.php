@extends('layouts.master')

@section('content')
    <div class="text-center">
        <h1>Welcome to Job Finder App</h1>
        <hr/>

        @include('partials.flash_notification')
        @if(count($jobsList))
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Job Likes</th>
                        <th>User Likes</th>
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
                            <td>{{ $job->upvoters_count }}</td>
                            <td>{{ $job->user->user_votes }}</td>
                            <td>{{ $job->created_at }}</td>
                            <td>{{ $job->updated_at }}</td>
                            <td>
                                {!! Form::open(['route' => ['job.apply', $job->id], 'class' => 'form-inline', 'method' => 'post']) !!}
                                {!! Form::hidden('id', $job->id) !!}
                                {!! Form::submit('Response to job', ['class' => 'btn btn-success btn-xs']) !!}
                                {!! Form::close() !!}
                                @if(\Auth::user())
                                {!! Form::open(['route' => ['job.like'], 'class' => 'form-inline', 'method' => 'post']) !!}
                                {!! Form::hidden('id', $job->id) !!}
                                {!! Form::hidden('type', 'job') !!}
                                {!! Form::submit('Job Like', ['class' => 'btn btn-danger btn-xs','disabled'=>$job->has_upvoted]) !!}
                                {!! Form::close() !!}

                                {!! Form::open(['route' => ['job.like'], 'class' => 'form-inline', 'method' => 'post']) !!}
                                {!! Form::hidden('id', $job->user->id) !!}
                                {!! Form::hidden('type', 'user') !!}
                                {!! Form::submit('User Like', ['class' => 'btn btn-danger btn-xs','disabled' => $job->user->voted]) !!}
                                {!! Form::close() !!}
                                @endif

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
    </div>
@endsection