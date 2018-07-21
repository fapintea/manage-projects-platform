<?php

namespace Diploma\Http\Controllers;

use Diploma\User;
use Diploma\Project;
use Diploma\ArchivedProject;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Auth;
use Config;

/**
 *  @author: Fabian Emanuel Pintea
 *  Bachelor's degree project ACS UPB 2018 
 */
class ReportsController extends Controller
{
    public function getStudentName($student) {
        return $student->name;
    }

    public function index() {
        $teachers = User::whereIn('role_id', array(
            Config::get('constants.TEACHER_ROLE_ID'),
            Config::get('constants.ADMIN_ROLE_ID')
        ))->get();

        $teachers_report_data = array();
        foreach($teachers as $teacher) {
            $teachers_report_data[$teacher->id] = array();
            $teachers_report_data[$teacher->id]["name"] = $teacher->name;
            $teachers_report_data[$teacher->id]["nr_projects"] = count($teacher->projects);

            $students = array();
            foreach($teacher->projects as $project) {

                $students_array = $project->students->map(
                    function($student) {
                        return $student->name;
                    }
                )->toArray();
                $students = array_merge($students, $students_array);
            }
            $teachers_report_data[$teacher->id]["students"] = implode(", ", $students);
        }
        $teachers_report_data = collect($teachers_report_data)->sortBy('nr_projects')->toArray();



        return view('reports_statistics', compact('teachers_report_data', 'students_report_data'));
    }

    public function get_project_number() {
        $projects = Project::where('year', Carbon::now()->year)->get();

        $project_no = array();
        foreach($projects as $project) {
            if (!array_key_exists($project->teacher->name, $project_no))
                $project_no[$project->teacher->name] = 0;
            $project_no[$project->teacher->name]++;
        }

        return response()->json($project_no);
    }

    public function get_students_number() {
        $projects = Project::where('year', Carbon::now()->year)->get();

        $students_no = array();
        foreach($projects as $project) {
            if (!array_key_exists($project->teacher->name, $students_no))
                $students_no[$project->teacher->name] = 0;
            $students_no[$project->teacher->name] += count($project->students);
        }

        return response()->json($students_no);
    }

    public function get_assigned_students_number() {

        $students = User::whereIn('role_id', array(
            Config::get('constants.BACHELOR_ROLE_ID'),
            Config::get('constants.MASTER_ROLE_ID')
        ))->get();
        $students_no = count($students);

        $assigned_students_no = 0;
        foreach($students as $student)
            if($student->project_id)
                $assigned_students_no++;

        return response()->json([
            "students_no" => $students_no,
            "assigned_students_no" => $assigned_students_no
        ]);
    }

    public function generate_report_1() {
        $projects = Project::where('year', Carbon::now()->year)->get();

        $data = array();
        foreach($projects as $project) {
            if (!array_key_exists($project->teacher->id, $data)) {
                $data[$project->teacher->id]['teacher_name'] = $project->teacher->name;
                $data[$project->teacher->id]['students_no'] = 0;
                $data[$project->teacher->id]['projects_no'] = 0;
                $data[$project->teacher->id]['students'] = array();
            }
            $data[$project->teacher->id]['students_no'] += count($project->students);
            $data[$project->teacher->id]['projects_no']++;

            $students_array = $project->students->map(
                function($student) {
                    return $student->name;
                }
            )->toArray();
            $data[$project->teacher->id]['students'] = array_merge($data[$project->teacher->id]['students'], $students_array);
        }

        $report_data = [];
        $report_data[] = ['id_profesor', 'nume_profesor', 'nr_proiecte', 'nr_studenti', 'studenti'];
        foreach($data as $id => $teacher_info) {
            $report_data[] = [
                $id,
                $teacher_info['teacher_name'],
                $teacher_info['projects_no'],
                $teacher_info['students_no'],
                implode(", ", $teacher_info['students'])
            ];
        }

        Excel::create('Raport profesori', function($excel) use ($report_data) {
            $excel->setTitle('Raport profesori');
            $excel->setCreator(Auth::user()->name);
            $excel->setDescription('Raport cu profesori - studenti');

            $excel->sheet('sheet1', function($sheet) use ($report_data) {
                $sheet->fromArray($report_data, null, 'A1', false, false);

                $sheet->setWidth('A', 10);
                $sheet->setWidth('B', 30);
                $sheet->setWidth('C', 10);
                $sheet->setWidth('D', 10);
                $sheet->setWidth('E', 300);
            });
        })->download('xlsx');
    }

    public function generate_report_2() {
        $students = User::whereIn('role_id', array(
            Config::get('constants.BACHELOR_ROLE_ID'),
            Config::get('constants.MASTER_ROLE_ID')
        ))->get();

        $report_data = [];
        $report_data[] = ['id_student', 'nume_student', 'proiect', 'nume_profesor'];
        foreach($students as $student) {
            $report_data[] = [
                $student->id,
                $student->name,
                $student->project ? $student->project->title : "NULL",
                $student->project ? $student->project->teacher->name : "NULL"
            ];
        }

        Excel::create('Raport studenti', function($excel) use ($report_data) {
            $excel->setTitle('Raport studenti');
            $excel->setCreator(Auth::user()->name);
            $excel->setDescription('Raport cu studenti - proiecte');

            $excel->sheet('sheet1', function($sheet) use ($report_data) {
                $sheet->fromArray($report_data, null, 'A1', false, false);

                $sheet->setWidth('A', 10);
                $sheet->setWidth('B', 30);
                $sheet->setWidth('C', 70);
                $sheet->setWidth('D', 50);
            });
        })->download('xlsx');
    }

    public function generate_report_3($year) {
        $projects = ArchivedProject::where('year', $year)->get();

        $report_data = [];
        $report_data[] = ['id_proiect', 'titlu_proiect', 'nume_profesor', 'data_lansarii', 'studenti'];
        foreach($projects as $project) {
            $report_data[] = [
                $project->id,
                $project->title,
                $project->teacher_name,
                $project->post_date,
                implode(", ", $project['students'])
            ];
        }

        $fileName = 'Raport proiecte arhivate ' . $year . '.xlsx';
        $file = Excel::create($fileName, function($excel) use ($report_data) {
            $excel->setTitle('Raport proiecte arhivate');
            $excel->setCreator(Auth::user()->name);

            $excel->sheet('sheet1', function($sheet) use ($report_data) {
                $sheet->fromArray($report_data, null, 'A1', false, false);

                $sheet->setWidth('A', 10);
                $sheet->setWidth('B', 70);
                $sheet->setWidth('C', 30);
                $sheet->setWidth('D', 20);
                $sheet->setWidth('E', 30);
            });
        });

        $file = $file->string('xlsx');

        return response()->json([
            'name' => $fileName,
            'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,".base64_encode($file)
        ]);
    }
}
