{{--    @author Fabian Emanuel Pintea
        Bachelor's degree project ACS UPB 2018  --}}
@extends('layouts.app')

@section('breadcrumbs')
    {{ Breadcrumbs::render('archive') }}
@endsection
@section('content')
<div class="container-fluid" style="margin-bottom: 10px;">
    <h2 id="title" class="text-center">Vizualizare proiecte propuse în anii anteriori</h2>
    <div class="col-md-3"></div>
    <div class="col-md-6">
        <div class="text-center">
            <span class="dropdown">
                <button class="btn btn-default btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    Selectează anul
                    <span class="caret"></span>
                </button>
                <ul id="years-dd" class="dropdown-menu" aria-labelledby="dropdownMenu1">
                    @foreach ($years as $year)
                        <li><a href="#">{{ $year }}</a></li>
                    @endforeach
                </ul>
            </span>
            <span>
                @if (Auth::user()->isAdmin())
                <a id="download-btn" href="#" class="btn btn-default btn-sm" style="display: none;"><i class="glyphicon glyphicon-download"></i> Descarcă raport</a>
                @endif
            </span>
        </div>
    </div>
    <div class="col-md-3"></div>
</div>

<div class="table-responsive">
    <table class="table table-striped table-bordered archive-table"></table>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $(".dropdown-menu li a").click(function(){

            var year = $(this).text();

            $(this).parents(".dropdown").find('.btn').html(year + ' <span class="caret"></span>');
            $(this).parents(".dropdown").find('.btn').val($(this).data('value'));

            var table = $(this).parents("div.container-fluid").find("table");

            $.ajax({
                type: 'get',
                url: '{!! URL::to('find_projects') !!}',
                data: {'year': year},
                dataType: 'json',
                success: function(data) {
                    document.getElementById('title').innerHTML = "Proiectele de licenţă propuse în anul " + year;

                    table_head = "<thead><tr>";
                    table_head += "<th>#</th>";
                    table_head += "<th>Tema</th>";
                    table_head += "<th>Profesor Coordonator</th>";
                    table_head += "<th>Data lansării</th>";
                    table_head += "<th>Studenţi</th>";
                    table_head += "</tr></thead>";

                    table_body = "<tbody>";
                    for(var i = 0; i < data.length; i++) {
                        table_body += "<tr><th>" + i.toString() + "</th><td>" + data[i].title + "</td><td>" + data[i].teacher_name
                            + "</td><td>" + data[i].post_date + "</td><td>";
                        for(var j = 0; j < data[i].students.length; j++) {
                            table_body += "<div>" + data[i].students[j] + "</div>";
                        }
                        table_body += "</td></tr>";
                    }
                    table_body += "</tbody>";

                    table.html(" ");
                    table.append(table_head + table_body);
                },
                error: function() {

                }
            });

            $('#download-btn').show();
            $('#download-btn').click(function() {
                $.ajax({
                    type: "GET",
                    url: "{{ url('create_archived_projects_excel') }}/" + year,
                    success: function(data) {
                        var a = document.createElement("a");
                        a.href = data.file;
                        a.download = data.name;
                        document.body.appendChild(a);
                        a.click();
                        a.remove();
                    }
                });
            });
        });
    })
</script>
@endsection