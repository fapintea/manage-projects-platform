{{--    @author Fabian Emanuel Pintea
        Bachelor's degree project ACS UPB 2018  --}}
@extends('layouts.app')

@section('breadcrumbs')
    {{ Breadcrumbs::render('superadmin_panel') }}
@endsection

@section('content')
    <div class="col-md-1"></div>
    <div class="col-md-10"><h1 class="text-center" style="margin-bottom: 3%;">Administrare drepturi de admin</h1></div>
    <div class="col-md-1"></div>
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Profesor</th>
                <th>
                    <div class="col-md-3 text-center">
                        Oferă admin
                    </div>
                    <div class="col-md-3 text-center">
                        Revocă admin                        
                    </div>
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($teachers as $index => $teacher)
                <tr>
                    <td class="col-md-1">{{ $index }}</td>
                    <td class="col-md-3">{{ $teacher->name }}</td>
                    <td class="col-md-8">
                        <div id="left-col-{{ $index }}" class="col-md-3 text-center">
                        @if (!$teacher->isAdmin())
                            <a  id="grant-{{ $teacher->id }}"
                                data-toggle="modal"
                                data-target="#admin-grant-modal"
                                data-userid="{{ $teacher->id }}"
                                data-name="{{ $teacher->name }}"
                                class="grant-btn btn btn-sm btn-primary">
                                Oferă ADMIN
                            </a>
                        @else
                            <a id="admin-{{ $teacher->id }}" class="btn btn-sm btn-success disabled">ADMIN</a>
                        @endif
                        </div>
                        <div id="right-col-{{ $index }}" class="col-md-3 text-center">
                            <a  id="revoke-{{ $teacher->id }}"
                                data-toggle="modal"
                                data-target="#admin-revoke-modal"
                                data-userid="{{ $teacher->id }}"
                                data-name="{{ $teacher->name }}"
                                class="revoke-btn btn btn-sm btn-danger"
                                @if (!$teacher->isAdmin()) 
                                    style="display: none;"
                                @endif >
                                Revocare ADMIN
                            </a>
                        </div>
                        <div id="notification-{{ $teacher->id }}" class="alert alert-info col-md-6" style="display: none; font-size: 70%; padding: 1.2%; margin-bottom: 0;"></div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

@include('modals.admin_panel.grant_admin')
@include('modals.admin_panel.revoke_admin')

@section('scripts')
<script>
    $('#admin-grant-modal').on('show.bs.modal', function(event) {
        var button  = $(event.relatedTarget);
        var modal   = $(this);
        var name    = button.data("name");
        var userid  = button.data("userid");

        modal.find("#name").html(name);
        
        $('#grant-admin-btn').click(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'post',
                url: '{!! URL::to("/grant_admin") !!}',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: {
                    'teacher_id': userid
                },
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        // Display notification
                        var notification = $('#notification-' + data.teacher.id);
                        notification.show();
                        notification.html(data.message);
                        notification.fadeTo(4000, 500).slideUp(500, function(){
                            notification.slideUp(500);
                        });  

                        // Display the right buttons
                        $('#admin-grant-modal').modal('hide');
                        $('#grant-' + data.teacher.id).replaceWith("<a id=\"admin-" + data.teacher.id + "\" class=\"btn btn-sm btn-success disabled\">ADMIN</a>");
                        
                        var revokeBtn = "<a id=\"revoke-" + data.teacher.id + "\" data-toggle=\"modal\" data-target=\"#admin-revoke-modal\" data-userid=\"" + data.teacher.id + "\" data-name=\"" + data.teacher.name + "\" class=\"revoke-btn btn btn-sm btn-danger\">Revocare ADMIN</a>";
                        $('#revoke-' + data.teacher.id).show();
                    } else {
                    }
                },
                error: function(data) {
                }
            });
        });
    });

    $('#admin-revoke-modal').on('show.bs.modal', function(event) {
        var button  = $(event.relatedTarget);
        var modal   = $(this);
        var name    = button.data("name");
        var userid  = button.data("userid");

        modal.find("#name").text(name);

        $('#revoke-admin-btn').click(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'post',
                url: '{!! URL::to("/revoke_admin") !!}',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: {
                    'teacher_id': userid
                },
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        // Display notification
                        var notification = $('#notification-' + data.teacher.id);
                        notification.show();
                        notification.html(data.message);
                        notification.fadeTo(4000, 500).slideUp(500, function(){
                            notification.slideUp(500);
                        }); 
                        
                        $('#admin-revoke-modal').modal('hide');

                        // Display the right buttons
                        var grantBtn = " \
                        <a  id=\"grant-" + data.teacher.id + "\" \
                            data-toggle=\"modal\" \
                            data-target=\"#admin-grant-modal\" \
                            data-userid=\"" + data.teacher.id + "\" \
                            data-name=\"" + data.teacher.name + "\" \
                            class=\"revoke-btn btn btn-sm btn-primary\"> \
                            Oferă ADMIN \
                        </a>";
                        $('#admin-' + data.teacher.id).replaceWith(grantBtn);
                        $('#revoke-' + data.teacher.id).hide();
                    } else {
                    }
                },
                error: function(data) {
                }
            })
        });
    });

</script>
@endsection