<?php

namespace App\Http\Controllers;

use App\Models\MyClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = Auth::id();

        $classes = MyClass::where('teacher', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('classes', ['classes' => $classes]);
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
        $class = new MyClass();

        $class->name = $request->name;
        $class->semester = $request->semester;
        $class->year = $request->year;
        $class->teacher = $request->teacher;
        $class->save();

        return redirect()->route('class.index')->with('message', 'Thêm lớp học thành công');
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
        $class = MyClass::find($id);

        if (!$class) {
            return redirect()->back()->with('error', 'Lớp học không tồn tại');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'semester' => 'required|in:1,2,Hè',
            'year' => 'required|string|max:255',
        ]);

        $class->name = $request->name;
        $class->semester = $request->semester;
        $class->year = $request->year;
        $class->save();

        return redirect()->back()->with('message', 'Cập nhật lớp học thành công');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $class = MyClass::find($id);

        if ($class) {
            DB::table('my_classes')->where('id', $id)->delete();
            session()->flash('message', 'Xóa lớp học thành công');
        } else {
            session()->flash('error', 'Không tìm thấy lớp học.');
        }

        return redirect()->back();
    }
}
