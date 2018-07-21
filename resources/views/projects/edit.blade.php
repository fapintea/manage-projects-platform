{{--    @author Fabian Emanuel Pintea
        Bachelor's degree project ACS UPB 2018  --}}
@extends("layouts.app")

@section('breadcrumbs')
    @if (isset($project))
        {{ Breadcrumbs::render('edit_project', $project) }}
    @else
        {{ Breadcrumbs::render('add_project') }}
    @endif
@endsection

@section('content')
<div class="container-fluid">
    <h3 class="text-center">
        {{ isset($project) ? 'Editare proiect' : 'Adăugare proiect' }}
    </div>
    <div class="col-md-2"></div>
    <div class="panel panel-default col-md-8">
        <div class="panel-body">
            <div class="container-fluid">
                @if (isset($project))
                    @include('forms.edit_project')
                @else
                    @include('forms.add_project')
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-2"></div>
</div>
@endsection