<?php

/**
 *  @author Fabian Emanuel Pintea
 *  Bachelor's degree project ACS UPB 2018
 */
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', 'ProjectController@index')
        ->name('home');

    // Profile related routes
    Route::get('/my_profile', 'HomeController@my_profile')
        ->name('my_profile');
    Route::post('/update_email', 'HomeController@update_email')
        ->name('update_email');

    Route::get('/logout', 'Auth\LoginController@logout')
        ->name('logout');

    // Projects related views
    Route::get('/projects', 'ProjectController@index')
        ->name('current-projects');

    Route::get('/archive', 'ProjectController@show_archive')
        ->name('archived-projects');

    Route::get('/find_projects', 'ProjectController@find_projects')
        ->name('find-projects');

    // Project show view
    Route::get('/projects/{id}/show', 'ProjectController@show')
        ->name('projects.show');

    Route::group(['middleware' => 'student'], function() {

        Route::post('/projects/choose_project', 'ProjectController@choose_project')
            ->name('projects.choose_project');
        Route::get('/create_diploma_file', 'HomeController@generateDiplomaFile')
            ->name('create_diploma_file');
    });

    Route::group(['middleware' => 'adminOrTeacher'], function() {

        Route::get('/my_projects', 'ProjectController@my_projects')
            ->name('my_projects');

        Route::get('/my_projects/{id}/edit', 'ProjectController@edit')
            ->name('projects.edit');

        Route::get('/my_projects/{id}/delete', 'ProjectController@destroy')
            ->name('projects.delete');

        Route::get('/projects/create', 'ProjectController@create')
            ->name('projects.create');

        // Projects related actions
        Route::post('/projects/store','ProjectController@store')
            ->name('projects.store');

        Route::post('/projects/{id}/update', 'ProjectController@update')
            ->name('projects.update');

        // Students related actions
        Route::get('/my_students', 'UserController@my_students')
            ->name('my_students');

            // Requests related actions
        Route::post('/requests/accept', 'ProjectController@accept_request')
            ->name('requests.accept');
        Route::post('/requests/reject', 'ProjectController@reject_request')
            ->name('requests.reject');

        // My students related actions
        Route::post('/my_students/save', 'UserController@save_student')
            ->name('my_students.save');
        Route::get('/my_students/{id}/delete', 'UserController@unassign_student')
            ->name('student_project.delete');
    });

    Route::group(['middleware' => 'superadmin'], function() {

        // Superadmin panel
        Route::get('/admin_administration', 'UserController@admin_administration')
            ->name('admin_administration');
        Route::post('/grant_admin', 'UserController@grant_admin')
            ->name('grant_admin');
        Route::post('/revoke_admin', 'UserController@revoke_admin')
            ->name('revoke_admin');
    });

    Route::group(['middleware' => 'superadminOrAdmin'], function() {

        // ReportsStatistics
        Route::get('/reports_statistics', 'ReportsController@index')
            ->name('reports_statistics');
        Route::post('/get_project_no', 'ReportsController@get_project_number')
            ->name('statistics.get_project_no');
        Route::post('/get_students_no', 'ReportsController@get_students_number')
            ->name('statistics.get_students_no');
        Route::post('/get_assigned_students_no', 'ReportsController@get_assigned_students_number')
            ->name('statistics.get_assigned_students_no');
        Route::get('/create_teachers_projects_excel', 'ReportsController@generate_report_1')
            ->name('create_teachers_projects_excel');
        Route::get('/create_students_projects_excel', 'ReportsController@generate_report_2')
            ->name('create_students_projects_excel');
        Route::get('/create_archived_projects_excel/{year}', 'ReportsController@generate_report_3')
            ->name('create_archived_projects_excel');
    });

    // Add comment view
    Route::post('/projects/{id}/add_comment', 'ProjectController@add_comment')
        ->name('project.add_comment');
});
