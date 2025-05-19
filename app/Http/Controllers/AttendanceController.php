<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Lesson;
use App\Models\MyClass;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function index(string $id)
    {
        $class = MyClass::where('id', $id)->first();
        $attendances = Lesson::where('class', $id)
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('attendances', ['class' => $class, 'attendances' => $attendances]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $lesson = new Lesson();
        $lesson->class = $request->class;
        $lesson->date = $request->date;
        $lesson->time = $request->time;
        $lesson->save();

        $students = Student::where('class',$lesson->class)->get();
        $savedLesson = Lesson::where('class', $lesson->class)->latest()->first();
        foreach($students as $student){
            $attendance = new Attendance();
            $attendance->lesson = $savedLesson->id;
            $attendance->student = $student->id;
            $attendance->status = 'Vắng';
            $attendance->save();
        }

        return redirect()->back()->with('message', 'Thêm điểm danh thành công');
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
        $class = MyClass::find($id);

        $class->late_limit = $request->late_limit;
        $class->absent_limit = $request->absent_limit;
        $class->save();

        return redirect()->back()->with('message', 'Điều chỉnh thành công');
    }

    public function lesson_update(Request $request, string $id)
    {
        $lesson = Lesson::find($id);

        $lesson->date = $request->date;
        $lesson->time = Carbon::parse($request->time)->format('H:i');
        $lesson->save();

        return redirect()->back()->with('message', 'Chỉnh sửa buổi điểm danh thành công');
    }

    public function destroy(string $id)
    {
        $form = Lesson::find($id);

        if ($form) {
            DB::table('lessons')->where('id', $id)->delete();
            session()->flash('message', 'Xóa buổi điểm danh thành công');
        } else {
            session()->flash('error', 'Không tìm thấy buổi điểm danh.');
        }

        return redirect()->back();
    }
}
