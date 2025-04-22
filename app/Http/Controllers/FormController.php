<?php

namespace App\Http\Controllers;

use App\Mail\AttendNotiMail;
use App\Models\Attendance;
use App\Models\Lesson;
use App\Models\MyClass;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class FormController extends Controller
{
    public function getQrCode(string $id)
    {
        $encodedId = Crypt::encrypt($id);
        $url = route('form.index', ['id' => $encodedId]);
        $qrCode = QrCode::size(500)->generate($url);
        return view('qrCode', compact('qrCode'));
    }

    public function index(string $id)
    {
        $decodedId = Crypt::decrypt($id);

        $lesson = Lesson::find($decodedId);

        $time = $lesson->time;
        $late = MyClass::where('id',$lesson->class)->first()->late_limit;
        $absent = MyClass::where('id',$lesson->class)->first()->absent_limit;

        return view('form', ['lesson' => $lesson, 'time' => $time, 'late' => $late, 'absent' => $absent]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
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
        $lesson = Crypt::decrypt($id);

        $attendance = Attendance::where('lesson',$lesson)->where('student',$request->student)->first();

        $device = md5($request->ip() . $request->userAgent());

        if (Attendance::where('device', $device)->where('lesson', $lesson)->exists()) {
            return redirect()->back()->with('error', 'Bạn đã điểm danh trên thiết bị này.');
        }

        if (!$attendance) {
            return redirect()->back()->with('error', 'Không tìm thấy sinh viên trong lớp');
        }

        $request->validate([
            'status' => 'required|in:có,vắng,trễ',
        ]);

        DB::table('attendances')
            ->where('lesson', $lesson)
            ->where('student', $request->student)
            ->update(['status' => $request->status, 'datetime' => $request->datetime,'device' => $device]);

        if ($request->receive_email == 1) {
            $student = Student::where('id', $request->student)->first();
            $fullName = $student->surname . ' ' . $student->forename;
            $lesson_obj = Lesson::where('id', $lesson)->first();
            $class = MyClass::where('id', $lesson_obj->class)->first();

            Mail::to('hdvp2578@gmail.com')->send(new AttendNotiMail(        //$student->email
                $fullName,
                $student->id,
                $request->status,
                $request->datetime,
                $class->name,
                $lesson_obj->date,
                $lesson_obj->time
            ));
        }

        return view('response');
    }

    public function destroy(string $id)
    {
        //
    }
}
