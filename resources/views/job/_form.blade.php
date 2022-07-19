    <!-- Name Field -->
    <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
        {!! Form::label('title', 'Title', ['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-6">
            {!! Form::text('title', null, ['placeholder' => 'Your title','class' => 'form-control']) !!}
            <span class="help-block text-danger">
                {{ $errors -> first('title') }}
            </span>
        </div>
    </div>
    <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
        {!! Form::label('description', 'Description', ['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-6">
            {!! Form::textarea('description', null, ['placeholder' => 'Your description','class' => 'col-sm-12 form-control', 'style' => 'resize:none']) !!}
            <span class="help-block text-danger">
                {{ $errors -> first('description') }}
            </span>
        </div>
    </div>