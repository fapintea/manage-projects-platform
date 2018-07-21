{{--    @author Fabian Emanuel Pintea
        Bachelor's degree project ACS UPB 2018  --}}
@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Autentificare</div>
                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                            <label for="username" class="col-md-4 control-label">Utilizator</label>

                            <div class="col-md-6">
                                <input id="username" type="username" class="form-control" name="username" value="{{ old('username') }}" required autofocus>

                                @if ($errors->has('username'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Parolă</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Login
                                </button>
                            </div>
                        </div>
                        <div id="info-login" class="form-group text-center">
                            Pentru clarificări sau detalii legate de lucrările de diplomă vă puteţi adresa la: <strong>diploma[at]cs.pub.ro.</strong>
                        </div>
                        <div id="info-login" class="form-group text-center">
                            Pentru studenţi: puteţi vizualiza proiectele propuse până la data curentă folosind credenţialele corespunzătoare contului vostru de <strong>cs.curs</strong>.
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Descriere</div>
                <div class="panel-body">
                    <div class="form-group">
                        Acest site este destinat organizarii proiectelor de diplomă în Facultatea de Automatică şi Calculatoare din cadrul Universităţii Politehnice Bucureşti. Vine în sprijinul profesorilor şi al studenţilor aflaţi în ani terminali. 
                    </div>
                    <div class="form-group">
                    Studenţii pot consulta proiectele propuse de cadrele universitare din facultate accesând secţiunea <i>Proiecte propuse</i>.
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-heading">Calendarul proiectului de licenţă</div>
            <div class="panel-body">
                <table class="table table-striped info-table">
                    <thead></thead>
                    <tbody>
                        <tr>
                            <td>12 Noiembrie 2017</td>
                            <td>Publicarea temelor de diplomă</td>
                        </tr>
                        <tr>
                            <td>10 Decembrie 2017</td>
                            <td>Data limită pentru alegerea temelor de către studenţi</td>
                        </tr>
                        <tr>
                            <td>19 Februarie 2018</td>
                            <td>Data limită pentru depunerea formularelor de înscriere pentru diplomă la secretariatul catedrei (ED100)</td>
                        </tr>
                        <tr>
                            <td>Februarie - Mai 2018</td>
                            <td>Pregătirea şi redactarea proiectului de diplomă</td>
                        </tr>
                        <tr>
                            <td>Mai 2018</td>
                            <td>Sesiunea de comunicări studenţeşti</td>
                        </tr>
                        <tr>
                            <td>Iunie 2018</td>
                            <td>Predarea proiectelor de diplomă</td>
                        </tr>
                        <tr>
                            <td>Iulie 2018</td>
                            <td>Susţinerea proiectelor de diplomă</td>
                        </tr>
                        <tr>
                            <td>T.B.A.</td>
                            <td>Data limită pentru depunerea formularelor de înscriere pentru a doua sesiune a examenului de diplomă.</td>
                        </tr>
                        <tr>
                            <td>Septembrie 2018</td>
                            <td>A doua sesiune de susţinere a proiectelor de diplomă</td>
                        </tr>
                    </tbody>
                </table>

                <div class="form-group">
                    <strong>Pentru mai multe informaţii şi detalii de ultimă oră consultaţi</strong>: <a href="http://wiki.cs.pub.ro/studenti/diploma/2017-2018">Wiki-ul de informare al Departamentului Calculatoare.</a>
                </div>
                <div class="form-group">
                    Se recomandă ca lucrarea de licenţă să aibă aproximativ 40 de pagini (aproximativ înseamnă +/- 5 pagini) fără reproduceri de cod sau alte materiale.
                </div$(".project-notice").fadeTo(5000, 500).slideUp(500, function(){
                    $(".project-notice").slideUp(500);
                });>
                <div class="form-group">
                    Folosind datele de autentificare crespunzătoare contului vostru, puteţi vizualiza proiectele depuse. Pentru inscrierea la temele de diplomă, studenţii au posibilitatea să trimită o cerere profesorilor în cadrul platformei.
                </div>
                <div class="form-group">
                    <h4>Anunţuri</h4>
                    <ul>
                        <li>[20 Octombrie 2017 s-au încarcat datele pentru anul 2017-2018]</li>
                    </ul>
                </div>
                
                <div class="form-group">
                    Proiecte din anii precedenţi 2003 - 2017 pot fi vizualizate în secţiunea <i>Arhivă proiecte</i> accesibilă după autentificare.
                </div>

                <div class="form-group">
                    <h4><a href="http://www.upb.ro/files/pdf/Admitere-2013/Regulament_Studii_Licenta_2013.pdf">Regulamentul privind activitatea profesională a studenţilor.Punctul V. Finalizarea studiilor</a></h4>
                    <h4><a href="http://wiki.cs.pub.ro/_media/studenti/regulament_etica_acs.pdf">Cod de conduită pentru studenţii Facultăţii de Automatică şi Calculatoare</a></h4>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection