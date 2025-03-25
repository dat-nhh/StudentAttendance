<?php

namespace App\Http\Controllers;

use App\Imports\StudentsImport;
use App\Models\Attendance;
use App\Models\ClassSession;
use App\Models\MyClass;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $id)
    {
        $class = MyClass::where('id', $id)->first();
        $students = Student::where('class', $id)->orderBy('forename', 'asc')->get();
        $sessions = ClassSession::where('class', $id)->orderBy('date', 'asc')->get();
        $sessionIds = $sessions->pluck('id');
        $attendances = Attendance::whereIn('session', $sessionIds)->get();

        return view('students', ['class' => $class, 'students' => $students, 'sessions' => $sessions, 'attendances' => $attendances]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $student = new Student();
        $student->id = $request->id;
        $student->surname = $request->surname;
        $student->forename = $request->forename;
        $student->student= $request->student;
        $student->save();

        return redirect()->back()->with('message', 'Thêm sinh viên thành công');
    }

    public function import(Request $request)
    {
        $request->validate([
            'student_file' => ['required', 'file'],
        ]);

        Excel::import(new StudentsImport($request->class), $request->file('student_file'));

        return redirect()->back()->with('message', 'Thêm sinh viên thành công');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $student = DB::table('students')->where('id', $id)->first();

        if ($student) {
            DB::table('students')->where('id', $id)->delete();
            session()->flash('message', 'Xóa sinh viên thành công');
        } else {
            session()->flash('error', 'Không tìm thấy sinh viên.');
        }

        return redirect()->back();
    }

    public function destroySelected(Request $request)
    {
        $ids = explode(',', $request->input('student_ids', ''));

        if (!empty($ids)) {
            foreach($ids as $id){
                DB::table('students')->where('id', $id)->delete();
            }
            return redirect()->back()->with('message', 'Xóa sinh viên thành công');
        }

        return redirect()->back()->with('error', 'Không tìm thấy sinh viên.');
    }
}
