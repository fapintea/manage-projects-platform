{{--    @author Fabian Emanuel Pintea
        Bachelor's degree project ACS UPB 2018  --}}
@extends('layouts.app')

@section('breadcrumbs')
    {{ Breadcrumbs::render('reports_statistics') }}
@endsection

@section('content')
    <div class="col-md-1"></div>
    <div class="col-md-10"><h1 class="text-center" style="margin-bottom: 3%;">Statistici şi generare de rapoarte</h1></div>
    <div class="col-md-1"></div>

    <div class="col-md-12" style="margin-bottom: 10%; text-align: -webkit-center;">
        <div>
            <table class="table">
                <thead>
                    <tr class="text-center" style="text-align: center;">
                        <strong>Generare Rapoarte</strong>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <i class="glyphicon glyphicon-menu-right" data-toggle="collapse" data-target="#report1" aria-expanded="false" aria-controls="report1" style="cursor: pointer;"></i>
                            Raport cu numele profesorilor, numărul de proiecte, numărul şi numele studenţilor
                            <div class="collapse" id="report1" style="margin-top: 3%; margin-bottom: 3%;">
                                <table id="report1" class="table-striped">
                                    <thead>
                                        <th class="report-table">Profesor</th>
                                        <th class="report-table">Număr proiecte</th>
                                        <th class="report-table" style="padding-left: 3%">Studenţi</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($teachers_report_data as $index => $teacher)
                                            <tr>
                                                <td class="report-table">{{ $teacher["name"] }}</td>
                                                <td class="report-table">{{ $teacher["nr_projects"] }}</td>
                                                <td class="report-table">{{ $teacher["students"] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </td>
                        <td><a href="{{ url('create_teachers_projects_excel') }}" class="btn btn-default btn-sm pull-right"><i class="glyphicon glyphicon-download"></i> Descarcă</a></td>
                    </tr>
                    <tr>
                        <td>
                            Raport cu numele studenţilor şi proiectele asignate
                        </td>
                        <td><a href="{{ url('create_students_projects_excel') }}" class="btn btn-default btn-sm pull-right"><i class="glyphicon glyphicon-download"></i> Descarcă</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div id="assigned_students_no"></div>
        <div id="projects_no"></div>
        <div id="students_no"></div>
    </div>
@endsection

@section('scripts')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load("current", {packages:["corechart"]});
    google.charts.setOnLoadCallback(drawBarChart);

    function drawBarChart() {

        $.ajax({
            type: 'post',
            url: '{!! URL::to("/get_assigned_students_no") !!}',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: {},
            dataType: 'json',
            success: function(result) {
                // Define the chart to be drawn.
                var data = google.visualization.arrayToDataTable([
                    ['Label', 'Studenţi asignaţi unui proiect', 'Număr total studenţi'],
                    ['',  result.assigned_students_no, result.students_no]
                ]);

                var chart = new google.visualization.BarChart(document.getElementById('assigned_students_no'));
                var options = {
                    title: 'Numărul de studenţi asignaţi unui proiect relativ la numărul total de studenţi',
                    isStacked: false,
                    width: 1000,
                    height: 300,
                    chartArea: {
                        width: '60%'
                    }
                };

                chart.draw(data, options);
            },
            error: function(err) {
                console.log(err);
            }
        });
    }
</script>
<script type="text/javascript">
    google.charts.load('current', {packages: ['corechart']});
    google.charts.setOnLoadCallback(drawProjectNoChart);

    function drawProjectNoChart() {

        $.ajax({
            type: 'post',
            url: '{!! URL::to("/get_project_no") !!}',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: {},
            dataType: 'json',
            success: function(result) {
                // Define the chart to be drawn.
                var data = new google.visualization.DataTable();
                data.addColumn('string', 'Teacher');
                data.addColumn('number', 'Number');

                var plot_data = [];
                for (var teacher in result) {
                    plot_data.push([teacher, result[teacher]]);
                }
                data.addRows(plot_data);

                var chart = new google.visualization.PieChart(document.getElementById('projects_no'));
                var options = {
                    'pieHole': 0.5,
                    'title': 'Numărul de proiecte propuse de fiecare profesor',
                    'width': 1000,
                    'height': 600,
                };
                chart.draw(data, options);
            },
            error: function(err) {
                console.log(err);
            }
        });
    }
</script>
<script type="text/javascript">
    google.charts.load('current', {packages: ['corechart']});
    google.charts.setOnLoadCallback(drawStudentNoChart);

    function drawStudentNoChart() {

        $.ajax({
            type: 'post',
            url: '{!! URL::to("/get_students_no") !!}',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: {},
            dataType: 'json',
            success: function(result) {
                // Define the chart to be drawn.
                var data = new google.visualization.DataTable();
                data.addColumn('string', 'X');
                data.addColumn('number', 'Număr studenţi asignaţi');

                var plot_data = [];
                for (var teacher in result) {
                    plot_data.push([teacher, result[teacher]]);
                }
                data.addRows(plot_data);

                var chart = new google.visualization.LineChart(document.getElementById('students_no'));
                var options = {
                    title : 'Numărul de studenţi asignaţi pentru fiecare profesor',
                    width: 1000,
                    height: 600,
                    hAxis: {
                        showText: false,
                        showTextEvery: 3,
                        slantedText: true,
                    },
                    vAxis: {
                        title: 'Număr proiecte propuse'
                    },
                    series: {
                        1: {curveType: 'function'}
                    },
                    pointsVisible: true,
                    legend: {
                        position: "none"
                    }
                };
                chart.draw(data, options);
            },
            error: function(err) {
                console.log(err);
            }
        });
    }
</script>
@endsection