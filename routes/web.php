<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('class.index'); 
    } else {
        return view('auth.login'); 
    }
});

Route::middleware('auth')->group(function (){
    Route::get('classes', [ClassController::class, 'index'])->name('class.index');
    Route::post('classes', [ClassController::class, 'store'])->name('class.store');
    Route::post('classes/update/{class}', [ClassController::class, 'update'])->name('class.update');
    Route::delete('classes/delete/{class}', [ClassController::class, 'destroy'])->name('class.destroy');
    Route::delete('/student/destroy/selected', [StudentController::class, 'destroySelected'])->name('student.destroy.selected');
});

Route::middleware('auth')->group(function () {
    Route::get('students{id}', [StudentController::class, 'index'])->name('student.index');
    Route::post('students', [StudentController::class, 'store'])->name('student.store');
    // Route::get('students/{student}', [StudentController::class, 'update'])->name('student.update');
    Route::delete('students/{student}', [StudentController::class, 'destroy'])->name('student.destroy');
    Route::post('students/import', [StudentController::class, 'import'])->name('student.import');
});

Route::middleware('auth')->group(function () {
    Route::get('attendances{id}', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('attendances', [AttendanceController::class, 'store'])->name('attendance.store');
    // Route::get('attendances/{attendance}', [AttendanceController::class, 'update'])->name('attendance.update');
    Route::delete('attendances/{attendance}', [AttendanceController::class, 'destroy'])->name('attendance.destroy');
});

Route::get('DiemDanh/{id}', [FormController::class, 'index'])->name('form.index');
Route::post('DiemDanh/{id}', [FormController::class, 'update'])->name('form.update');
Route::get('QRCode/{id}', [FormController::class, 'getQrCode'])->name('form.qr');

Route::get('/dashboard', function () {
    return view('index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
