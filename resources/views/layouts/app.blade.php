{{--    @author Fabian Emanuel Pintea
        Bachelor's degree project ACS UPB 2018  --}}
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Diploma') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/header.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=PT+Serif" rel="stylesheet">
    @yield('css')

</head>
<body>
    <div id="app">
        <nav class="navbar" style="background: #173e43;">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand white-font" href="{{ url('home') }}"><img alt="Brand" src="{{ asset('img/acs.logo.png') }}" width="45"></a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav white-font">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle white-font" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Proiecte <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="{{ url('home') }}" class="white-font-mobile">Proiecte 2018</a></li>
                                @if (Auth::check() && (Auth::user()->isTeacher()))
                                    <li><a href="{{ url('my_projects') }}" class="white-font-mobile">Proiectele mele</a></li>
                                @endif
                                <li role="separator" class="divider"></li>
                                <li><a href="{{ url('archive') }}" class="white-font-mobile" >Arhivă proiecte</a></li>
                            </ul>
                        </li>
                        @if (Auth::check())
                            @if (Auth::user()->isStudent())
                                @if (isset(Auth::user()->project_id))
                                    <li><a class="white-font" href="{{ URL('/projects/' . Auth::user()->project_id . '/show') }}">Proiectul meu</a></li>
                                @endif
                                <li><a class="white-font" href="{{ url('create_diploma_file') }}"><span class="glyphicon glyphicon-download"></span> Fişa de diplomă (.docx)</a></li>
                            @endif
                            @if (Auth::user()->isTeacher())
                                <li><a class="white-font" href="{{ url('my_students') }}">Studenţii mei</a></li>
                            @endif
                            @if (Auth::user()->isSuperAdmin())
                                <li><a class="white-font" href="{{ url('admin_administration') }}">Administrare admini</a></li>
                            @endif
                            @if (Auth::user()->isAdmin())
                                <li><a class="white-font" href="{{ url('reports_statistics') }}">Rapoarte şi statistici</a></li>
                            @endif
                        @endif
                    </ul>
                    <ul class="nav navbar-nav navbar-right" style="margin-right: -2%">
                        @if (Auth::check())
                            <li class="dropdown" style="min-width: 200px">
                                <a href="#" class="dropdown-toggle white-font" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" style="padding-top: 1%; padding-bottom: 1%">
                                    <div><div class="text-center">{{ Auth::user()->name }} <span class="caret"></span></div>
                                        <div class="text-center" style="margin-left: 20%; margin-right: 20%; background: #6B8E23;">
                                            {{ Auth::user()->role->role_name }}
                                        </div>
                                    </div>
                                    {{-- <div>{{ Auth::user()->role->role_name }}</div> --}}
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="text-danger white-font-mobile" href="{{ url('my_profile') }}">
                                            <i class="glyphicon glyphicon-user"></i> Profilul meu
                                        </a>
                                    </li>
                                    <li>
                                        <a class="text-danger white-font-mobile" href="{{ url('logout') }}">
                                            <i class="glyphicon glyphicon-log-out"></i> Deconectare
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>
        <div class="container-fluid">
            <div class="col-md-1"></div>
            <div class="col-md-10">
                @yield('breadcrumbs')

                @yield('content')
            </div>
            <div class="col-md-1"></div>
        </div>
    </div><!-- <app -->
    
    <!-- Footer -->
    @include('footer')

    <!-- Scripts -->
    <script src="{{ asset('/js/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/tinymce/js/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        tinymce.init({ 
            selector: 'textarea',
            path_absolute: "{{ URL::to('/') }}",
            plugins: [
                "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                "searchreplace wordcount visualblocks visualchars code fullscreen",
                "insertdatetime media nonbreaking save table contextmenu directionality",
                "emoticons template paste textcolor colorpicker textpattern" 
            ],
            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify \
            | bullist numlist outdent indent | link image media",
            relative_urls: false
        });
    </script>
    @yield('scripts')
</body>
</html>
