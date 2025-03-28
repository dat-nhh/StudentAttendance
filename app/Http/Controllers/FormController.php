<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class FormController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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

        $time = $lesson->created_at->format('H:i');

        return view('form', ['lesson' => $lesson, 'time' => $time]);
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
        //
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
            ->update(['status' => $request->status, 'device' => $device]);

        return view('response');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
