{{--    @author Fabian Emanuel Pintea
        Bachelor's degree project ACS UPB 2018  --}}
{!! Form::open(['method' => 'POST', 'route' => 'projects.store', 'class' => 'form-horizontal', 'id' => 'add-project-form']) !!}

    {!! Form::token() !!}
    <div class="form-group">
        {{ Form::label('Tema') }}
        {{ Form::text('title', old('title'), ['class' => 'form-control', 'placeholder' => 'Tema proiectului']) }}
    </div>
    @if($errors->has('title'))
            <div class="alert alert-danger form-error">
                {{ $errors->first('title') }}
            </div>
    @endif

    <div class="form-group">
        {{ Form::label('Descriere') }}
        {{ Form::textarea('description', old('description'), 
            ['class' => 'form-control', 'placeholder' => 'Descrierea proiectului', 'id' => 'tinymce-editor'])
        }}
    </div>
    @if($errors->has('description'))
            <div class="alert alert-danger form-error">
                {{ $errors->first('description') }}
            </div>
    @endif

    <div class="form-group">
        {{ Form::label('Număr studenţi') }}
        {{ Form::text('nr_students', old('nr_students'), ['class' => 'form-control', 'placeholder' => 'Numărul studenţilor']) }}
    </div>
    @if($errors->has('nr_students'))
            <div class="alert alert-danger form-error">
                {{ $errors->first('nr_students') }}
            </div>
    @endif

    <div class="form-group">
        {{ Form::label('Bibliografie') }}
        <span class="glyphicon glyphicon-info-sign" style="margin-left: 10px;" title="Titluri bibliografice separate de newline"></span>
        {{ Form::textarea('references', old('references'), ['class' => 'form-control']) }}
    </div>
    @if($errors->has('references'))
            <div class="alert alert-danger form-error">
                {{ $errors->first('references') }}
            </div>
    @endif

    <div class="text-center" style="margin-top: 5%;">
        <a href="{{ url('/my_projects') }}" class="btn btn-md btn-danger">Anulează</a>
        <button id="add-btn" type="submit" class="btn btn-primary">Adaugă</button>
    </div>
    
{!! Form::close() !!}