<?php

namespace App\Http\Controllers;

use App\Models\MyClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClassController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $classes = MyClass::where('teacher', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('classes', ['classes' => $classes]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $class = new MyClass();
        $class->name = $request->name;
        $class->semester = $request->semester;
        $class->year = $request->year;
        $class->late_limit = 5;
        $class->absent_limit = 60;
        $class->teacher = $request->teacher;
        $class->save();

        return redirect()->route('class.index')->with('message', 'Thêm lớp học thành công');
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

        if (!$class) {
            return redirect()->back()->with('error', 'Lớp học không tồn tại');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'semester' => 'required|in:1,2,Hè',
            'year' => 'required|string|max:255',
            'late_limit' => 'required|integer',
            'absent_limit' => 'required|integer',
        ]);

        $class->name = $request->name;
        $class->semester = $request->semester;
        $class->year = $request->year;
        $class->late_limit = $request->late_limit;
        $class->absent_limit = $request->absent_limit;
        $class->save();

        return redirect()->back()->with('message', 'Cập nhật lớp học thành công');
    }

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
