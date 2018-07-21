<?php

namespace Diploma\Http\Controllers;

use Illuminate\Http\Request;
use Diploma\User;
use Diploma\UserRequest;
use Diploma\Project;
use Auth;
use Config;
use DB;

use Carbon\Carbon;

/**
 *  @author Fabian Emanuel Pintea
 *  Bachelor's degree project ACS UPB 2018 
 */
class UserController extends Controller
{
    public function my_students() {
        $my_projects = Project::where('teacher_id', Auth::user()->id)
            ->where('year', Carbon::now()->year)
            ->get();

        // From a dict with "id" : value items we get the value array
        $my_projects_ids = array_map(function($x) {return $x["id"];}, $my_projects->toArray());
        $my_students = User::whereIn('project_id', $my_projects_ids)->get();

        $requests = UserRequest::whereIn('project_id', $my_projects_ids)->get();


        $my_available_projects = array();
        foreach ($my_projects as $project) {
            if (count($project->students) < $project->nr_students)
                array_push($my_available_projects, $project);
        }

        return view('teachers.my_students', compact('my_students', 'requests', 'my_available_projects'));
    }

    public function save_student(Request $request) {

        $project = Project::where('title', $request->project_name)->first();
        $student = User::where('id', $request->student_id)->first();
        if ($student != null) {
            // Update project on student side
            $student->project_id = $project->id;
            $student->update();

            return response()->json([
                "success" => true,
                "message" => "Studentul\a " . $student->name ." a fost asignat cu succes proiectului " . $project->title . ".",
                "project" => $project
            ]);
        }

        return response()->json([
            "success" => false,
            "message" => "Studentul\a " . $student->name ." nu a fost găsit în baza noastră de date."
        ]);
    }

    public function unassign_student(Request $request) {

        $user = User::where('id', $request->id)->first();

        if ($user != null) {
            $user->project_id = NULL;
            $user->save();
        }

        return redirect()->route('my_students');
    }

    public function admin_administration() {
        $teachers = User::whereIn('role_id', 
            array(
                Config::get('constants.TEACHER_ROLE_ID'),
                Config::get('constants.ADMIN_ROLE_ID'))
            )->get();

        return view('admin_administration', compact('teachers'));
    }

    public function grant_admin(Request $request) {

        $teacher = User::where('id', $request->teacher_id)->first();
        $teacher->role_id = Config::get('constants.ADMIN_ROLE_ID');
        $teacher->update();

        return response()->json([
            "success" => true,
            "teacher" => $teacher,
            "message" => $teacher->name . " a primit drepturi de admin!"
        ]);
    }

    public function revoke_admin(Request $request) {

        $teacher = User::where('id', $request->teacher_id)->first();
        $teacher->role_id = Config::get('constants.TEACHER_ROLE_ID');
        $teacher->update();

        return response()->json([
            "success" => true,
            "teacher" => $teacher,
            "message" => "Drepturile de admin pentru " . $teacher->name . " au fost revocate!"
        ]);
    }
}
