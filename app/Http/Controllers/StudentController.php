<?php

namespace App\Http\Controllers;

use App\Exports\StudentsExport;
use App\Imports\StudentsImport;
use App\Models\Attendance;
use App\Models\Lesson;
use App\Models\MyClass;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    public function index(string $id)
    {
        $class = MyClass::where('id', $id)->first();
        $students = Student::where('class', $id)->orderBy('forename', 'asc')->get();
        $lessons = Lesson::where('class', $id)->orderBy('date', 'asc')->get();
        $lessonIds = $lessons->pluck('id');
        $attendances = Attendance::join('lessons', 'attendances.lesson', '=', 'lessons.id')
            ->whereIn('attendances.lesson', $lessonIds)
            ->orderBy('lessons.date', 'asc')
            ->select('attendances.*')
            ->get();

        return view('students', ['class' => $class, 'students' => $students, 'lessons' => $lessons, 'attendances' => $attendances]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'surname' => 'required|string|max:255',
            'forename' => 'required|string|max:255',
            'id' => 'required|string|max:255',
            'email' => 'nullable|email',
        ]);

        $student = new Student();
        $student->id = $request->id;
        $student->surname = $request->surname;
        $student->forename = $request->forename;
        $student->email = $request->email;
        $student->class = $request->class;
        $student->save();

        $lessons = Lesson::where('class', $student->class)->get();
        foreach($lessons as $lesson){
            $attendance = new Attendance();
            $attendance->lesson = $lesson->id;
            $attendance->student = $student->id;
            $attendance->status = 'Vắng';
            $attendance->save();
        }

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

    public function export(string $id)
    {
        $class = MyClass::where('id', $id)->first();
        $students = Student::where('class', $id)->orderBy('forename', 'asc')->get();
        $lessons = Lesson::where('class', $id)->orderBy('date', 'asc')->get();
        $lessonIds = $lessons->pluck('id');
        $attendances = Attendance::join('lessons', 'attendances.lesson', '=', 'lessons.id')
            ->whereIn('attendances.lesson', $lessonIds)
            ->select('attendances.*')
            ->get();

        $fileName = $class->name . '.xlsx';

        return Excel::download(new StudentsExport($students, $attendances, $lessons), $fileName);
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        $student = Student::find($id);

        if (!$student) {
            return redirect()->back()->with('error', 'Sinh viên không tồn tại');
        }

        $request->validate([
            'surname' => 'required|string|max:255',
            'forename' => 'required|string|max:255',
            'id' => 'required|string|max:255',
            'email' => 'nullable|email',
        ]);

        $student->surname = $request->surname;
        $student->forename = $request->forename;
        $student->id = $request->id;
        $student->email = $request->email;
        $student->save();

        DB::table('attendances')
            ->where('lesson', $request->lesson)
            ->where('student', $request->id)
            ->update(['status' => $request->status]);

        return redirect()->back()->with('message', 'Cập nhật sinh viên thành công');
    }

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
