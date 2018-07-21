{{--    @author Fabian Emanuel Pintea
        Bachelor's degree project ACS UPB 2018  --}}
@extends('layouts.app')

@section('breadcrumbs')
    {{ Breadcrumbs::render('home') }}
@endsection

@section('content')

@if (Auth::user()->isStudent())
    @if (isset(Auth::user()->project))
        <div class="project-notice alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <span class="glyphicon glyphicon-info-sign"></span> Proiectul tău de diplomă este: {{ Auth::user()->project->title }}
        </div>
    @else
        <div class="project-notice alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <span class="glyphicon-glyphicon-info-sign"></span> Nu eşti asignat la niciun proiect de diplomă. Poţi alege dintre proiectele propuse mai jos.
        </div>
    @endif
@endif

<div class="container-fluid">
    <div class="col-md-1"></div>
    <div class="col-md-10"><h1 class="text-center" style="margin-bottom: 3%;">Lista proiectelor propuse până la data de <span style="white-space: nowrap;"> {{ Carbon\Carbon::now('Europe/Bucharest')->today()->toDateString() }}</span></h1></div>
    <div class="col-md-1"></div>
</div>
<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col" style="width: 40%;">Temă</th>
                <th scope="col">Profesor</th>
                <th scope="col"></th>
                <th scope="col">Dată publicare</th>
                <th scope="col"></th>
                <th scope="col">Studenţi</th>
                <th scope="col" style="width: 2%;">Număr Studenţi</th>
                @if (Auth::user()->isSuperAdmin())
                    <th scope="col">Acţiuni</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($projects as $index => $project)
            <tr>
                <th scope="row">{{ $index }}</th>
                <td>
                    {{ $project->title }}
                @if (   Auth::user()->isStudent() &&
                        !isset(Auth::user()->project) &&
                        $project->nr_students != count($project->students) &&
                        !in_array($project->id, $requests_sent)
                    )
                    <a id="choose-{{ $project->id }}" class="choose-btn" href="#" data-idproject="{{ $project->id }}" style="font-size: 80%">Alege proiect</a>
                    <div id="notice-{{ $project->id }}" class="alert alert-info" style="display: none; font-size: 70%; padding: 1.2%; margin-bottom: 0;"></div>
                @endif
                </td>
                <td>{{ $project->teacher->name }}</td>
                <td>
                    <a  id="info-btn"
                        data-toggle="modal"
                        data-target="#project-info-modal"
                        data-teachername="{{ $project->teacher->name }}"
                        data-title="{{ $project->title }}"
                        data-references="{{ !empty($project->references) ? implode("|", $project->references) : null }}"
                        data-description="{{ $project->description }}">
                        <i class="glyphicon glyphicon-info-sign"></i>
                    </a>
                </td>
                <td>{{ $project->post_date }}</td>
                <td>
                @if ($project->nr_students > count($project->students))
                    <span id="remove-glyph" class="glyphicon glyphicon-remove"></span>
                @else
                    <span id="ok-glyph" class="glyphicon glyphicon-ok"></span>
                @endif
                </td>
                <td>
                    @foreach ($project->students as $student)
                        <div>{{ $student->name }}</div>
                    @endforeach
                </td>
                <td>{{ $project->nr_students }}</td>
                @if (Auth::user()->isSuperAdmin())
                    <td>
                        <div class="col-md-6 action-icon" style="padding-top: 7px !important;">
                            <a href="{{ route('projects.edit', $project->id) }}">
                                <i class="glyphicon glyphicon-edit"></i>
                            </a>
                        </div>
                        <div class="col-md-6 action-icon" style="padding-top: 7px !important;">
                            <a onclick="return confirm('Sunteţi sigur că doriţi să ştergeţi proiectul cu titlul: \'{{ $project->title }}\' ?')" href="{{ route('projects.delete', $project->id) }}">
                                <i class="glyphicon glyphicon-remove"></i>
                            </a>
                        </div>
                    </td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@include('modals.projects.info')

@section('scripts')
<script>
$(document).ready(function() {

    $(".project-notice").fadeTo(5000, 500).slideUp(500, function(){
        $(".project-notice").slideUp(500);
    });

    $('.choose-btn').on('click', function(e) {
        e.preventDefault();
        var project_id = $(this).data('idproject');

        $.ajax({
                type: 'post',
                url: '{!! URL::to("/projects/choose_project") !!}',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: {'project_id': project_id},
                dataType: 'json',
                success: function(data) {
                    $('#notice-' + project_id).html(data["message"]);
                    $('#notice-' + project_id).show();

                    $('#notice-' + project_id).fadeTo(3000, 500).slideUp(500, function(){
                        $('#notice-' + project_id).slideUp(500);
                    });
                    $('#choose-' + project_id).hide();
                },
                error: function(data) {
                    $('#notice-' + project_id).html(data["message"]);
                    $('#notice-' + project_id).show();

                    $('#notice-' + project_id).fadeTo(3000, 500).slideUp(500, function(){
                        $('#notice-' + project_id).slideUp(500);
                    });
                }
            });
    });

    $(function() {

        $('#project-info-modal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);

            var teacher_name = button.data('teachername');
            var title = button.data('title');
            var description = button.data('description');
            var references = button.data('references');

            var modal = $(this)

            // Display teacher name
            modal.find('#project-teacher').text(teacher_name);

            // Display project title
            modal.find('#project-title').text(title);

            // Display project description
            if (description.length)
                modal.find('#project-description').html(description);
            else {
                modal.find('#project-description').empty();
                modal.find('#project-description').append('<div class="alert alert-warning" style="padding: 1%;">Proiectul curent nu are descriere !</div>');
            }

            // Display references
            if (references.length) {
                modal.find("#ref-list").empty();
                references = references.split("|");
                $.each(references, function(key, val) {
                    modal.find("#ref-list").append('<li>' + val + '</li>');
                });
            } else {
                modal.find('#project-references').empty();
                modal.find('#project-references').append('<div class="alert alert-warning" style="padding: 1%;">Bibliografie inexistentă pentru acest proiect !</div>');
            }
        });
    });
});
</script>
@endsection