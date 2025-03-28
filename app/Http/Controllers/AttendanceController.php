<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Lesson;
use App\Models\MyClass;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $id)
    {
        $class = MyClass::where('id', $id)->first();
        $attendances = Lesson::where('class', $id)->orderBy('date', 'desc')
            ->paginate(10);

        return view('attendances', ['class' => $class, 'attendances' => $attendances]);
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
        $lesson = new Lesson();
        $lesson->class = $request->class;
        $lesson->date = $request->date;
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
