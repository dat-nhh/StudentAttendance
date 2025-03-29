@extends('layouts.layout')

@section('content')
    <div class="card mt-2">
        <div class="card-header d-flex">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" href="#">Sinh viên</a>
                </li>
                <li class="nav-item">
                    <form action="{{ route('attendance.index', $class->id) }}" method="GET">
                        @csrf
                        <button type="submit" class="nav-link text-secondary">
                            Điểm danh
                        </button>
                    </form>
                </li>
            </ul>

            <div class="ms-auto justify-content-center">
                <form action="{{ route('class.export', $class->id) }}" method="post" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-info btn-sm">
                        <i class="fas fa-file-export"></i> Xuất DS
                    </button>
                </form>

                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal">
                    <i class="bi bi-trash"></i> Xóa
                </button>

                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
                    <i class="bi bi-plus"></i> Thêm
                </button>
            </div>
        </div>

        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Xác nhận xóa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Bạn có chắc chắn muốn xóa các sinh viên đã chọn không?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <form id="deleteForm" action="{{ route('student.destroy.selected') }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="student_ids" id="student_ids" value="">
                            <button type="submit" class="btn btn-danger">Xóa</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">Thêm sinh viên</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('student.import') }}" method="post" enctype="multipart/form-data" class="p-3">
                        @csrf
                        <label for="file">Nhập danh sách sinh viên</label>
                        <div class="input-group">
                            <input type="file" class="form-control" name="student_file" id="student_file" accept=".xls, .xlsx" required>
                            <input type="hidden" id="class" name="class"  value="{{ $class->id }}">
                            <button type="submit" class="btn btn-primary">Gửi</button>
                        </div>
                    </form>
                    <hr width="100%">
                    <form action="{{ route('student.store') }}" method="POST" class="p-3">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group row mb-1">
                                Nhập thông tin sinh viên lẻ
                            </div>
                            <div class="form-group row mb-1">
                                <label for="surname" class="col-sm-3 col-form-label">Họ</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="surname" name="surname" placeholder="Nhập họ và tên đệm sinh viên" required>
                                </div>
                            </div>
                            <div class="form-group row mb-1">
                                <label for="forename" class="col-sm-3 col-form-label">Tên</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="forename" name="forename" placeholder="Nhập tên sinh viên" required>
                                </div>
                            </div>
                            <div class="form-group row mb-1">
                                <label for="id" class="col-sm-3 col-form-label">MSSV</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="id" name="id" placeholder="Nhập mã số sinh viên" required>
                                </div>
                            </div>
                            <input type="hidden" id="class" name="class"  value="{{ $class->id }}">
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
            <div class="table-responsive" style="height: 75vh; overflow-y: auto;">
                <table class="table table-bordered table-hover table-striped mt-4">
                    <thead>
                        <tr class="table-primary">
                            <th width='1%' class="sticky-top"><input type="checkbox" id="selectAll"></th>
                            <th class="sticky-top" style="min-width: 300px; width: 20%;">Họ Tên</th>
                            <th class="sticky-top" width="5%">MSSV</th>
                            @foreach ($lessons as $lesson)
                                <th class="sticky-top" width="5%">{{ \Carbon\Carbon::parse($lesson->date)->format('d-m') }}</th>
                            @endforeach
                            <th class="sticky-top"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                            <tr>
                                <td>
                                    <input type="checkbox" class="student-checkbox" value="{{ $student->id }}">
                                </td>
                                <td>{{ $student->surname}} {{ $student->forename}}</td>
                                <td>{{ $student->id }}</td>
                                @foreach ($attendances as $attendance)
                                    @if ($attendance->student == $student->id)
                                        <td class="{{ $attendance->status == 'vắng' ? 'bg-danger' : ($attendance->status == 'trễ' ? 'bg-warning' : '') }}">
                                            {{ $attendance->status }}
                                        </td>
                                    @endif
                                @endforeach
                                <td></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <script>
            document.getElementById('deleteModal').addEventListener('show.bs.modal', function () {
                const checkboxes = document.querySelectorAll('.student-checkbox:checked');
                const ids = Array.from(checkboxes).map(checkbox => checkbox.value);
                document.getElementById('student_ids').value = ids.join(',');
            });

            document.getElementById('selectAll').addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('.student-checkbox');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });
        </script>
    </div>
@endsection