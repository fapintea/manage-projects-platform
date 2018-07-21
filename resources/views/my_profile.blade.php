{{--    @author Fabian Emanuel Pintea
        Bachelor's degree project ACS UPB 2018  --}}
@extends('layouts.app')

@section('breadcrumbs')
    {{ Breadcrumbs::render('my_profile') }}
@endsection

@section('content')
<div class="col-md-2"></div>
<div class="col-md-8">
    <div class="well col-md-12">
        <h3 class="text-center" style="margin-bottom: 10%">Profilul meu</h3>
            
        <div class="col-md-6" style="text-align: right; font-weight: bold;">
            <div>Nume</div>
            @if (Auth::user()->isStudent())
                <div>Grupă</div>
                <div>Rol utilizator</div>
                @if (Auth::user()->isBachelorStudent())
                    <div>Proiect licenţă</div>
                @else
                    <div>Proiect dizertaţie</div>
                @endif 
            @else
                <div>Rol utilizator</div>
            @endif
            <div style="padding-top: 1.5%">Email</div>
        </div>

        <div class="col-md-6">
            <div>{{ Auth::user()->name }}</div>
            @if (Auth::user()->isStudent())
                <div>{{ Auth::user()->group }}</div>
                @if (Auth::user()->isBachelorStudent())
                    <div>Student Licenţă</div>
                @elseif (Auth::user()->isMasterStudent())
                    <div>Student Master</div>
                    <div>{{ Auth::user()->masters_name }}</div>
                @endif
                @if (isset(Auth::user()->project))
                    <div>    
                        {{ Auth::user()->project->title }}
                    </div>
                @else
                    <span class="alert-danger">Niciun proiect asignat !</span>
                @endif
            @elseif (Auth::user()->isSuperAdmin())
                <div>Superadmin</div>
            @elseif (Auth::user()->isAdmin())
                <div>Admin</div>
            @elseif (Auth::user()->isTeacher())
                <div>Profesor</div>
            @endif
            <div>
                <div class="col-md-8" style="padding-left: 0; padding-right: 0;">
                    <input id="mail" type="email" class="form-control" value="{{ Auth::user()->email }}" style="height: 31px;"/>
                </div>
                <div class="col-md-4" style="padding-left: 1%; padding-right: 0;">
                    <button id="save-btn" type="btn" class="btn btn-success btn-sm" style="display: none;">Salvează</div>
                </div>
            </div>
        </div>
        <div id="notice" class="alert alert-info text-center" style="display: none;"></div>
    </div>
</div>
<div class="col-md-2"></div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $("#mail").click(function() {
            console.log("CLICK");
            $('#mail').prop('disabled', false);
            $('#save-btn').show();
        });

        $('#save-btn').click(function() {

            var email = $('#mail').val();
            if (!email.includes("@")) {
                $('#notice').html("Te rog să introduci o adresă de email validă !");
                $('#notice').show();

                $('#notice').fadeTo(3000, 500).slideUp(500, function(){
                    $('#notice').slideUp(500);
                });
                return;
            }

            $.ajax({
                type: 'post',
                url: '{!! URL::to("/update_email") !!}',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: {'email': email},
                dataType: 'json',
                success: function(data) {
                    $('#save-btn').hide();
                    $('#notice').html(data["message"]);
                    $('#notice').show();

                    $('#notice').fadeTo(3000, 500).slideUp(500, function(){
                        $('#notice').slideUp(500);
                    });
                },
                error: function(data) {
                    // error
                }
            });
        });

        $(".project-notice").fadeTo(5000, 500).slideUp(500, function(){
        $(".project-notice").slideUp(500);
    });
    });
</script>
@endsection