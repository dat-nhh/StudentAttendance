@extends('layouts.layout')

@section('content')
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
            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="bi bi-plus"></i> Thêm
            </button>
        </div>

        <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">Thêm điểm danh</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form action="{{ route('attendance.store') }}" method="POST" class="p-3">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" id="class" name="class"  value="{{ $class->id }}">
                            <div class="form-group row mb-1">
                                <label for="date" class="col-sm-3 col-form-label">Ngày</label>
                                <div class="col-sm-9">
                                    <input type="date" class="form-control" id="date" name="date" value="{{now()->format('Y-m-d')}}" required>
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
            @foreach ($attendances as $attendance)
                <div class="card my-2">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-9">
                                <h5 class="card-title">{{ \Carbon\Carbon::parse($attendance->date)->format('d-m-Y') }} {{ \Carbon\Carbon::parse($attendance->created_at)->format('H:i') }}</h5>
                            </div>
                            <div class="col-md-3 d-flex justify-content-end">
                                <form action="{{ route('form.qr',$attendance->id) }}" method="get" target="_blank">
                                    @csrf
                                    <button type="submit" class="btn btn-info btn-sm mx-2">
                                        QR Code
                                    </button>
                                </form>
                                 <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal{{$attendance->id}}">
                                    <i class="bi bi-trash"></i> Xóa
                                </button>

                                <div class="modal fade" id="deleteModal{{$attendance->id}}" tabindex="-1" aria-labelledby="deleteModalLabel{{$attendance->id}}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel{{$attendance->id}}">Xác nhận xóa</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Bạn có chắc chắn muốn xóa <strong>{{ \Carbon\Carbon::parse($attendance->date)->format('d-m-Y') }}</strong> không?
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
                </div>
            @endforeach

            {!! $attendances->links() !!}

        </div>
    </div>
@endsection