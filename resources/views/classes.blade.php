@extends('layouts.layout')

@section('content')

@php
$currentMonth = date('n'); 
$currentYear = date('Y');
$semFlag = 1;

if ($currentMonth >= 2 && $currentMonth < 6) {
    $yearValue = ($currentYear - 1) . '-' . $currentYear;
    $semFlag = 2;
} elseif ($currentMonth >= 6 && $currentMonth < 9) {
    $yearValue = ($currentYear - 1) . '-' . $currentYear;
    $semFlag = 3;
} elseif ($currentMonth >= 9 || $currentMonth < 2) {
    $yearValue = $currentYear . '-' . ($currentYear + 1);
}
@endphp
<h2>Quản lý lớp học</h2>
<div class="card mt-5">
    <div class="card-header d-grid gap-2 d-md-flex justify-content-md-end">
        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="bi bi-plus"></i> Thêm
            </button>
        </div>
    </div>

    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Thêm lớp học</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('class.store') }}" method="POST" class="p-3">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name" class="form-label">Tên lớp</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Nhập tên lớp" required>
                        </div>
                        <div class="form-group">
                            <label for="semester" class="form-label">Học kỳ</label>
                            <select name="semester" id="semester" class="form-control">
                                <option {{ $semFlag == '1' ? 'selected' : '' }} value="1">1</option>
                                <option {{ $semFlag == '2' ? 'selected' : '' }} value="2">2</option>
                                <option {{ $semFlag == '3' ? 'selected' : '' }} value="Hè">Hè</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="year" class="form-label">Năm học</label>
                            <input type="text" class="form-control" id="year" name="year" value="{{ $yearValue }}" placeholder="Nhập năm học" required>
                        </div>
                        <input type="hidden" name='teacher' value="{{ Auth::user()->id }}">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">Lưu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    
    <div class="card-body">
            <table class="table table-bordered table-striped mt-4">
                <thead>
                    <tr>
                        <td>Tên lớp</td>
                        <td>Học kỳ</td>
                        <td>Năm học</td>
                        <td>Chức năng</td>
                    </tr>
                </thead>
    
                <tbody>
                @foreach($classes as $class)
                    <tr>
                        <td>{{$class->name}}</td>
                        <td>{{$class->semester}}</td>
                        <td>{{$class->year}}</td>
                        <td class="d-flex justify-content-start gap-2">
                            <form action="{{ route('student.index', $class->id) }}" method="GET">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-info">
                                    <i class="bi bi-person-fill-check"></i> Điểm danh
                                </button>
                            </form>

                            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $class->id }}">
                                <i class="bi bi-pencil-square"></i> Sửa
                            </button>

                            <div class="modal fade" id="editModal{{ $class->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editModalLabel">Chỉnh sửa thông tin</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('class.update', $class->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="name" class="form-label">Tên lớp</label>
                                                    <input type="text" class="form-control" id="name" name="name" placeholder="Nhập tên lớp" value="{{ $class->name }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="semester" class="form-label">Học kỳ</label>
                                                    <select name="semester" id="semester" class="form-control">
                                                        <option value="1" {{ $class->semester == '1' ? 'selected' : '' }}>1</option>
                                                        <option value="2" {{ $class->semester == '2' ? 'selected' : '' }}>2</option>
                                                        <option value="Hè" {{ $class->semester == 'Hè' ? 'selected' : '' }}>Hè</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="year" class="form-label">Năm học</label>
                                                    <input type="text" class="form-control" id="year" name="year" placeholder="Nhập năm học" value="{{ $class->year }}" required>
                                                </div>
                                                <div class="form-group row mb-1">
                                                    <label for="late_limit" class="col-sm-6 col-form-label">Ngưỡng đánh trễ</label>
                                                    <div class="col-sm-3">
                                                        <input type="number" class="form-control" id="late_limit" name="late_limit" value="{{$class->late_limit}}" required>
                                                    </div>
                                                    <div class="col-sm-3 mt-2">
                                                        <p>Phút</p>
                                                    </div>
                                                </div>
                                                <div class="form-group row mb-1">
                                                    <label for="absent_limit" class="col-sm-6 col-form-label">Ngưỡng đánh vắng</label>
                                                    <div class="col-sm-3">
                                                        <input type="number" class="form-control" id="absent_limit" name="absent_limit" value="{{$class->absent_limit}}" required>
                                                    </div>
                                                    <div class="col-sm-3 mt-2">
                                                        <p>Phút</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                                <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal{{$class->id}}">
                                <i class="bi bi-trash"></i> Xóa
                            </button>

                            <div class="modal fade" id="deleteModal{{$class->id}}" tabindex="-1" aria-labelledby="deleteModalLabel{{$class->id}}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteModalLabel{{$class->id}}">Xác nhận xóa</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Bạn có chắc chắn muốn xóa lớp <strong>{{ $class->name }}</strong> không?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                            <form action="{{ route('class.destroy', $class->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Xóa</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
    
            </table>
            
            {!! $classes->links() !!}

    </div>
</div>  

@endsection