@extends('layouts.layout')

@section('content')
    <h3>{{$class->name}}</h3>
    <h6>Học kỳ {{$class->semester}} | Năm {{$class->year}}</h6>
    <div class="card mt-2">
        <div class="card-header d-flex justify-content-between align-items-center">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <form action="{{ route('student.index', $class->id) }}" method="GET">
                        @csrf
                        <button type="submit" class="nav-link text-secondary">
                            Sinh viên
                        </button>
                    </form>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="#">Điểm danh</a>
                </li>
            </ul>

            <div class="ms-auto justify-content-center">
                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#editModal">
                    <i class="bi bi-gear"></i> Điều chỉnh
                </button>

                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
                    <i class="bi bi-plus"></i> Thêm
                </button>
            </div>
        </div>

        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Điều chỉnh thời gian</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form action="{{ route('attendance.update',$class->id) }}" method="POST" class="p-3">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" id="class" name="class" value="{{ $class->id }}">
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
                            <button type="submit" class="btn btn-primary">Lưu</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">Thêm buổi điểm danh</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form action="{{ route('attendance.store') }}" method="POST" class="p-3">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" id="class" name="class" value="{{ $class->id }}">
                            <div class="form-group row mb-1">
                                <label for="date" class="col-sm-3 col-form-label">Ngày</label>
                                <div class="col-sm-9">
                                    <input type="date" class="form-control" id="date" name="date" value="{{now()->format('Y-m-d')}}" required>
                                </div>
                            </div>
                            <div class="form-group row mb-1">
                                <label for="date" class="col-sm-3 col-form-label">Giờ</label>
                                <div class="col-sm-9">
                                    <input type="time" class="form-control" id="time" name="time" value="{{now()->format('H:i')}}" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-primary">Lưu</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">Thêm buổi điểm danh</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form action="{{ route('attendance.store') }}" method="POST" class="p-3">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" id="class" name="class" value="{{ $class->id }}">
                            <div class="form-group row mb-1">
                                <label for="date" class="col-sm-3 col-form-label">Ngày</label>
                                <div class="col-sm-9">
                                    <input type="date" class="form-control" id="date" name="date" value="{{now()->format('d/m/Y')}}" required>
                                </div>
                            </div>
                            <div class="form-group row mb-1">
                                <label for="date" class="col-sm-3 col-form-label">Giờ</label>
                                <div class="col-sm-9">
                                    <input type="time" class="form-control" id="time" name="time" value="00:00" required>
                                </div>
                            </div>
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
            <div class="container">
                <div class="row">
                    @foreach ($attendances as $attendance)
                        <div class="col-md-6">
                            <div class="card my-2">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="card-title mb-0">{{ \Carbon\Carbon::parse($attendance->date)->format('d/m/Y') }} {{ \Carbon\Carbon::parse($attendance->time)->format('H:i') }}</h5>
                                        <div>
                                            <form action="{{ route('form.qr', $attendance->id) }}" method="get" target="_blank" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-info btn-sm mx-1">
                                                    <i class="bi bi-qr-code"></i> QR Code
                                                </button>
                                            </form>

                                            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $attendance->id }}">
                                                <i class="bi bi-pencil-square"></i> Sửa
                                            </button>

                                            <div class="modal fade" id="editModal{{ $attendance->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="editModalLabel">Chỉnh sửa thông tin</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{ route('attendance.lesson_update', $attendance->id) }}" method="POST">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <div class="form-group row mb-1">
                                                                    <label for="date" class="col-sm-3 col-form-label">Ngày</label>
                                                                    <div class="col-sm-9">
                                                                        <input type="date" class="form-control" id="date" name="date" value="{{$attendance->date}}" required>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row mb-1">
                                                                    <label for="date" class="col-sm-3 col-form-label">Giờ</label>
                                                                    <div class="col-sm-9">
                                                                        <input type="time" class="form-control" id="time" name="time"  value="{{ \Carbon\Carbon::parse($attendance->time)->format('H:i') }}" required>
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

                                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $attendance->id }}">
                                                <i class="bi bi-trash"></i> Xóa
                                            </button>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="deleteModal{{ $attendance->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $attendance->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel{{ $attendance->id }}">Xác nhận xóa</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Bạn có chắc chắn muốn xóa <strong>{{ \Carbon\Carbon::parse($attendance->date)->format('d/m/Y') }} {{ \Carbon\Carbon::parse($attendance->time)->format('H:i') }}</strong> không?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                                    <form action="{{ route('attendance.destroy', $attendance->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Xóa</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {!! $attendances->links() !!}
        </div>
    </div>
</div>
@endsection