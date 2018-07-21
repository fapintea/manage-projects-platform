<?php

namespace Diploma\Http\Controllers;

use Diploma\User;
use Diploma\Project;
use Diploma\Comment;
use Diploma\ArchivedProject;
use Diploma\UserRequest;
use Diploma\Http\Controllers\StudentMail;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Diploma\Http\Requests\ProjectRequest;
use DB;
use Auth;
use Mail;
use JavaScript;

/**
 *  @author: Fabian Emanuel Pintea
 *  Bachelor's degree project ACS UPB 2018 
 */
class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = Project::where('year', Carbon::now()->year)->get();

        $requests_sent = UserRequest::where('student_id', Auth::user()->id)
            ->pluck('project_id')
            ->toArray();

        return view('home', compact('projects', 'requests_sent'));
    }

    public function my_projects() {

        $projects = Project::where('teacher_id', Auth::user()->id)
            ->where('year', Carbon::now()->year)
            ->get();

        return view('projects.my_projects', compact('projects'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('projects.edit');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProjectRequest $request)
    {

        $project = new Project();
        $project->title = $request->title;
        $project->description = $request->description;
        $project->teacher_id = Auth::user()->id;   // TODO - get the user ID from the teachers TABLE
        $project->post_date = str_replace('/', '.', Carbon::now('Europe/Bucharest')->format('d/m/Y'));
        $project->nr_students = $request->nr_students;
        $project->year = Carbon::now('Europe/Bucharest')->year;
        $project->references = explode("\r\n", $request->references);
        $project->save();

        $projects = Project::where('teacher_id', Auth::user()->id)
            ->where('year', Carbon::now()->year)
            ->get();

        return redirect()->route('my_projects', ['projects' => $projects]);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $project = Project::where('id', $id)->first();

        $comments = Comment::where('project_id', $project->id)
            ->get();


        return view('projects.show', compact('project', 'comments'));
    }

    public function show_archive() {

        $years_data = DB::table('archived_projects')
            ->select('year')
            ->where('year', '<', date('Y'))
            ->groupBy('year')
            ->get();

        $years = [];
        foreach($years_data as $year)
            array_push($years, $year->year);

        return view('archive', compact('years'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Diploma\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $project = Project::find($request->id);

        return view('projects.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Diploma\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(ProjectRequest $request) {
        $project = Project::find($request->id);

        if ($project == null)
            return view('errors.messages', [], 404);
        else {
            $project->update($request->all());
            $project->references = explode("\r\n", $request->references);
            $project->save();
        }

        $projects = Project::where('teacher_id', Auth::user()->id)
            ->where('year', Carbon::now()->year)
            ->get();

        if (Auth::user()->isSuperAdmin())
            return redirect()->route('home');
        return redirect()->route('my_projects', ['projects' => $projects]);
    }

    public function find_projects(Request $request) {

        $data = ArchivedProject::where('year', $request->year)->get();

        return response()->json($data);
    }

    public function choose_project(Request $request) {

        // Find the project
        $project = Project::where('id', $request->project_id)->first();
        
        if (count($project->students) == $project->nr_students)
            return response()->json([
                "message" => "Proiectul are deja numărul permis de membri."
            ]);

        
        $userRequest = UserRequest::where('student_id', Auth::user()->id)
            ->where('project_id', $request->project_id)
            ->first();
        if ($userRequest != null)
            return response()->json([
                "message" => "Ai trimis deja o cerere către profesorul care a propus proiectul."
            ]);

        $newRequest = new UserRequest();
        $newRequest->student_id = Auth::user()->id;
        $newRequest->project_id = $request->project_id;
        $newRequest->save();

        // send mail to teacher
        if ($project->teacher->email != null) {
            Mail::to($project->teacher->email)->queue(new StudentMail(Auth::user()->name, $project->title));
        }

        return response()->json([
            "message" => "O cerere a fost trimisă către profesorul care a propus proiectul."
        ]);
    }


    public function accept_request(Request $request) {

        $project = Project::where('id', $request->project_id)->first();
        if ($project == null)
            return response()->json([
                "success" => false,
                "message" => "Proiectul cu id-ul " . $request->project_id . " nu a fost găsit în baza de date."
            ]);
        
        // If there are already the maximum number of students allowed in the project
        if (count($project->students) == $project->nr_students)
            return response()->json([
                "success" => false,
                "message" => "Proiectul \"" . $project->title ."\" are deja numărul admis de studenţi."
            ]);

        // Set the student project_id
        $user = User::where('id', $request->student_id)->first();
        if ($user != null) {
            if ($user->project != null) {
                return response()->json([
                    "success" => false,
                    "message" => "Studentul \"" . $user->name ."\" are deja un proiect asignat."
                ]);
            }
            $user->project_id = $request->project_id;
            $user->update();
        }

        // Delete the request entry 
        $userRequest = UserRequest::where('student_id', $user->id)
            ->where('project_id', $request->project_id)
            ->first();
        if($userRequest != null)
            $userRequest->delete(); 

        return response()->json([
            "success" => true,
            "message" => "Studentul " . $request->student_name . " a fost acceptat pentru proiectul \"" . $project->title . "\"."
        ]);
    }

    public function reject_request(Request $request) {

        $project = Project::where('id', $request->project_id)->first();
        if ($project == null)
            return response()->json([
                "success" => false,
                "message" => "Proiectul cu id-ul " . $request->project_id . " nu a fost găsit în baza de date."
            ]);

        $user = User::where('id', $request->student_id)->first();
        if ($user == null)
            return response()->json([
                "success" => false,
                "message" => "Studentul " . $request->student_name . " nu a fost găsit în baza de date."
            ]);

        // Delete the user request
        $userRequest = UserRequest::where('student_id', $user->id)->first();
        if ($userRequest != null)
            $userRequest->delete();
        
        return response()->json([
            "success" => true,
            "message" => "Cererea studentului " . $request->name . " a fost ştearsă."
        ]);
    }

    public function add_comment(Request $request) {
  
        $comment = new Comment();
        $comment->content = $request->content;
        $comment->user_id = Auth::user()->id;
        $comment->project_id = $request->id;
        $comment->comment_datetime = Carbon::now('Europe/Bucharest')->toDayDateTimeString();;
        $comment->save();

        return redirect()->route('projects.show', ['id' => $request->id]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \Diploma\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $project = Project::findOrFail($id);

        if($project->teacher_id == Auth::user()->id) {
            $project->delete();
            return redirect()->route('my_projects');
        }
        
        return view('errors.messages', [], 403);
    }
}
