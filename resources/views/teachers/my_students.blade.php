{{--    @author Fabian Emanuel Pintea
        Bachelor's degree project ACS UPB 2018  --}}
@extends('layouts.app')

@section('breadcrumbs')
    {{ Breadcrumbs::render('my_students') }}
@endsection

@section('content')
<div id="info-box"></div>
<div class="col-md-9">
    <h2 class="text-center" style="margin-bottom: 20px;">Studenţii mei</h2>
    @if (count($my_students) == 0)
        <div class="alert alert-danger">Nu aveţi niciun student asignat proiectelor dumneavoastră!</div>
    @else
    <div class="panel panel-default table-responsive">

        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Student</th>
                    <th>Grupă</th>
                    <th>Proiect diplomă</th>
                    <th class="text-center">Modificare / Eliminare</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($my_students as $student)
                    <tr>
                        <td>{{ $loop->index }}</td>
                        <td>{{ $student->name }}</td>
                        <td>{{ $student->group }}</td>
                        <td>
                            <div id="project-{{ $student->id }}">
                                {{ $student->project->title }}
                            </div>
                            <div id="edit-mode-{{ $student->id }}" style="display: none">
                                <div class="dropdown" style="margin-bottom: 5px;">
                                    <button class="btn btn-default dropdown-toggle" type="button" id="dropdown-{{ $student->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" style="white-space: normal;">
                                        {{ $student->project->title }}
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu1" style="white-space: normal;">
                                        <li><a href="#">{{ $student->project->title }}</a></li>
                                        @foreach ($my_available_projects as $project)
                                            <li><a href="#">{{ $project->title }}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                                <a  data-studentid="{{ $student->id }}" 
                                    class="save-btn btn btn-xs btn-success">
                                    <span class="glyphicon glyphicon-ok"></span> Salvează
                                </a>
                                <a  data-target="#delete-project-modal"
                                    data-studentid="{{ $student->id }}"
                                    class="cancel-btn btn btn-xs btn-danger">
                                    <span class="glyphicon glyphicon-remove"></span> Anulează
                                </a>
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="col-md-4 action-icon">
                                <a href="{{ URL('/projects/' . $student->project->id . '/show') }}"
                                    class="btn button-default">
                                    <span class="glyphicon glyphicon-comment"></span>
                                </a>
                            </div>
                            <div class="col-md-4 action-icon">
                                <a  data-studentid="{{ $student->id }}" 
                                    class="btn button-default edit-btn">
                                    <span class="glyphicon glyphicon-edit"></span>
                                </a>
                            </div>
                            <div class="col-md-4" style="padding-top: 5px;">
                                {!! Form::open(['method' => 'get', 'route' => ['student_project.delete', $student->id], 'class' =>'form-delete', 'style' => 'margin-bottom: 0;']) !!}
                                {!! Form::hidden('id', $student->id) !!}
                                {!! Form::button('<a style="margin-top: 5px !important;"><span class="glyphicon glyphicon-remove"></span></a>', array('class'=>'btn btn-link action-icon', 'type'=>'submit')) !!}
                                {!! Form::close() !!}
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
<div class="col-md-3">
    <h2 class="text-center" style="margin-bottom: 20px;">Cereri</h2>
    @if (count($requests) == 0)
        <div class="alert alert-danger">Nu există nicio cerere din partea studenţilor!</div>
    @endif

    @foreach ($requests as $request)
        <div class="alert alert-warning">
            <div style="margin-bottom: 5px;">{{ $request->student->name }} doreşte să realizeze proiectul <i>"{{ $request->project->title }}"</i> pentru diplomă.</div>
            <div class="text-center">
                <a  href="#"
                    data-studentid="{{ $request->student->id }}" 
                    data-projectid="{{ $request->project->id }}"
                    class="accept-btn btn btn-xs btn-success">
                    <span class="glyphicon glyphicon-ok"></span> Acceptă
                </a>
                <a  href="#"
                    data-studentid="{{ $request->student->id }}" 
                    data-projectid="{{ $request->project->id }}" 
                    class="reject-btn btn btn-xs btn-danger">
                    <span class="glyphicon glyphicon-remove"></span> Respinge
                </a>
            </div>
        </div>
    @endforeach
</div>
@endsection

@include('modals.project_user.delete')

@section('scripts')
<script>
    $(document).ready(function() {
        $(".accept-btn").click(function() {

            var student_id = $(this).data('studentid');
            var project_id = $(this).data('projectid');

            $.ajax({
                type: 'post',
                url: '{!! URL::to("/requests/accept") !!}',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: {
                    'student_id': student_id,
                    'project_id': project_id
                },
                dataType: 'json',
                success: function(data) {
                    if (data.success)
                        location.reload();
                    else {
                        // Display alert                    
                        box = $('#info-box');
                        box.removeClass();
                        box.html("");
                        box.addClass("alert info-alert alert-warning");
                        box.html('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' + data.message);
                    }
                },
                error: function(data) {

                    // Display alert
                    box = $('#info-box');
                    box.removeClass();
                    box.html("");
                    box.addClass("alert info-alert alert-danger");
                    box.html('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' + data.message);
                }
            });
        });

        $(".reject-btn").click(function() {

            var student_id = $(this).data('studentid');
            var project_id = $(this).data('projectid');

            $.ajax({
                type: 'post',
                url: '{!! URL::to("/requests/reject") !!}',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: {
                    'student_id': student_id,
                    'project_id': project_id
                },
                dataType: 'json',
                success: function(data) {
                    if (data.success)
                        location.reload();
                    else {
                        // Display alert                    
                        box = $('#info-box');
                        box.removeClass();
                        box.html("");
                        box.addClass("alert info-alert alert-warning");
                        box.html('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' + data.message);
                    }
                },
                error: function(data) {

                    // Display alert
                    box = $('#info-box');
                    box.removeClass();
                    box.html("");
                    box.addClass("alert info-alert alert-danger");
                    box.html('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' + data.message);
                }
            });
        });

        $(".edit-btn").click(function() {
            
            var student_id = $(this).data('studentid');

            $('#project-' + student_id).hide();
            $("#edit-mode-" + student_id).show();

        });

        $(".save-btn").click(function() {
            var student_id = $(this).data('studentid');

            var project_name = $('#dropdown-' + student_id).text();

            $.ajax({
                type: 'post',
                url: '{!! URL::to("/my_students/save") !!}',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: {
                    'project_name': project_name,
                    'student_id': student_id
                },
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        // Hide edit mode buttons
                        $("#edit-mode-" + student_id).hide();

                        // Update project if changed
                        $('#project-' + student_id).html(data.project.title);
                        $('#project-' + student_id).show();
                    }
                },
                error: function(data) {

                }
            });
        });

        $(".cancel-btn").click(function() {
            
            var student_id = $(this).data('studentid');
            
            // Hide edit mode buttons
            $("#edit-mode-" + student_id).hide();

            // Display project title
            $('#project-' + student_id).show();
        });

        $(".dropdown-menu li a").click(function() {
            var choice = $(this).text();

            $(this).parents(".dropdown").find('.btn').html(choice + ' <span class="caret"></span>');
            $(this).parents(".dropdown").find('.btn').val($(this).data('value'));
            
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