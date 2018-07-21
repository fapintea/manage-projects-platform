<?php
/**
 *  @author Fabian Emanuel Pintea
 *  Bachelor's degree project ACS UPB 2018
 */

// Proiecte 2018
Breadcrumbs::register('home', function ($breadcrumbs) {
    $breadcrumbs->push('Proiecte 2018', route('home'));
});

// Home -> Profilul meu
Breadcrumbs::register('my_profile', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Profilul meu', route('my_profile'));
});


// Proiecte 2018 > Proiectele mele
Breadcrumbs::register('my_projects', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Proiectele mele', route('my_projects'));
});

// Proiecte 2018 > Arhivă proiecte
Breadcrumbs::register('archive', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Arhivă proiecte', route('archived-projects'));
});

// Proiecte 2018 > Arhivă proiecte > Adăugare proiect
Breadcrumbs::register('add_project', function ($breadcrumbs) {
    $breadcrumbs->parent('my_projects');
    $breadcrumbs->push('Adăugare proiect', route('projects.create'));
});

// Proiecte 2018 > Arhivă proiecte > Editare proiect
Breadcrumbs::register('edit_project', function ($breadcrumbs, $project) {
    $breadcrumbs->parent('my_projects');
    $breadcrumbs->push('Editare proiect', route('projects.edit', $project->id));
});

// Proiecte 2018 -> My Students
Breadcrumbs::register('my_students', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Studenţii mei', route('my_students'));
});

// Proiecte 2018 -> My project
Breadcrumbs::register('my_project', function ($breadcrumbs, $project) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push($project->title, route('projects.show', $project->id));
});


// Proiecte 2018 -> SuperAdmin Panel
Breadcrumbs::register('superadmin_panel', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Administrarea admini', route('admin_administration'));
});

// Proiecte 2018 -> Reports and Statistics
Breadcrumbs::register('reports_statistics', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Rapoarte şi statistici', route('reports_statistics'));
});