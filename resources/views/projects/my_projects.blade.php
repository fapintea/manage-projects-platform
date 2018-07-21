                <!-- <th scope="col"></th> -->

@extends('layouts.app')

@section('breadcrumbs')
    {{ Breadcrumbs::render('my_projects') }}
@endsection

@section('content')
<div class="container-fluid">
    <div class="col-md-2"></div>
    <div class="col-md-8"><h1 class="text-center" style="margin-bottom: 3%;">Proiectele propuse de mine pentru anul 2017 - 2018</h1></div>
    <div class="col-md-2">
        <a href="{{ url('/projects/create') }}" class="btn btn-default btn-md pull-right add-project-btn"><i class="glyphicon glyphicon-plus"></i>&nbsp;Adaugă proiect</a>
    </div>
</div>

@if (count($projects) == 0)
    <div class="alert alert-danger">Nu aţi propus niciun proiect! Puteţi să o faceţi dând click pe '<strong>Adaugă proiect</strong>'.</div>
@else
<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col" style="width: 40%;">Temă</th>
                <th scope="col">Dată publicare</th>
                <th scope="col"></th>
                <th scope="col">Studenţi</th>
                <th scope="col" style="width: 2%;">Număr Studenţi</th>
                <th scope="col" style="text-align: center;">Acţiuni</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($projects as $index => $project)
            <tr>
                <th scope="row">{{ $index }}</th>
                <td>{{ $project->title }}</td>
                <td>{{ $project->post_date }}</td>
                <td>
                @if (count($project->students) != $project->nr_students)
                    <span class="glyphicon glyphicon-remove"></span>
                @else
                    <span class="glyphicon glyphicon-ok"></span>
                @endif
                </td>
                <td>
                    @foreach ($project->students as $student)
                        <div>{{ $student["name"] }}</div>
                    @endforeach
                </td>
                <td>{{ $project->nr_students }}</td>
                <td>
                    <div class="col-md-6 action-icon" style="padding-top: 7px !important;"><a href="{{ route('projects.edit', $project->id) }}"><span class="glyphicon glyphicon-edit"></span></a></div>
                    <div class="col-md-6">
                            {!! Form::open(['method' => 'get', 'route' => ['projects.delete', $project->id], 'class' =>'form-delete', 'style' => 'margin-bottom: 0']) !!}
                            {!! Form::hidden('title', $project->id) !!}
                            {!! Form::button('<div class="action-icon" style="font-size: 130%"><a><span class="glyphicon glyphicon-remove"></span></a></div>', array('class'=>'btn btn-link', 'type'=>'submit')) !!}
                            {!! Form::close() !!}
                    </div>
                </td>
            </tr>
            @endforeach  
        </tbody>
    </table>
</div>
@endif

@endsection

@include('modals.projects.delete', ['id' => 'delete-project-modal'])

@section('scripts')
<script>
    $(document).ready(function() {
        $(function() {
            $('#delete-project-modal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);

                var title = button.data('title');

                var modal = $(this);
                modal.find('#title').text(title);
            });
        });

        $('.form-delete').click(function(e) {
            e.preventDefault();

            var form = $(this);
            $('#confirm-delete').modal()
                .on('click', '#delete-btn', function() {
                    form.submit();
                });
        });
    });
</script>
@endsection