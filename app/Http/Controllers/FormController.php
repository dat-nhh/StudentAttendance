<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\ClassSession;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Carbon;

class FormController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getQrCode(string $id)
    {
        // $url = route('form.index', $id);
        $encodedId = base64_encode($id);
        $url = route('form.index', ['id' => $encodedId]);
        $qrCode = QrCode::size(500)->generate($url);
        return view('qrCode', compact('qrCode'));
    }

    public function index(string $id)
    {
        $decodedId = base64_decode($id);

        $classSession = ClassSession::find($decodedId);

        $time = $classSession->created_at->format('H:i');

        return view('form', ['session' => $classSession, 'time' => $time]);
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
    public function update(Request $request, string $session)
    {
        $attendance = DB::table('attendances')->where('session',$session)->where('student',$request->student)->first();

        if (!$attendance) {
            return redirect()->back()->with('error', 'Không tìm thấy sinh viên trong lớp');
        }

        $request->validate([
            'status' => 'required|in:có,vắng,trễ',
        ]);

        DB::table('attendances')
            ->where('session', $session)
            ->where('student', $request->student)
            ->update(['status' => $request->status]);

        return view('approve');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
