@extends('layouts.layout')

@section('content')
    <h3>{{$class->name}}</h3>
    <h6>Học kỳ {{$class->semester}} | Năm {{$class->year}}</h6>
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
                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#deleteAllModal">
                    <i class="bi bi-trash"></i> Xóa
                </button>

                <form action="{{ route('class.export', $class->id) }}" method="post" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="bi bi-file-earmark-arrow-down"></i> Xuất DS
                    </button>
                </form>

                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
                    <i class="bi bi-plus"></i> Thêm
                </button>
            </div>
        </div>

        <div class="modal fade" id="deleteAllModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
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
                            <div class="form-group row mb-1">
                                <label for="id" class="col-sm-3 col-form-label">Email</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="email" name="email" placeholder="Nhập email sinh viên">
                                </div>
                            </div>
                            <input type="hidden" id="class" name="class" value="{{ $class->id }}">
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
                            <th width='15%' class="sticky-top" style="min-width: 200px; width: 15%;"><input type="checkbox" id="selectAll"></th>
                            <th class="sticky-top" style="min-width: 300px; width: 20%;">Họ Tên</th>
                            <th class="sticky-top" width="5%">MSSV</th>
                            @foreach ($lessons as $lesson)
                                <th class="sticky-top" width="5%">{{ \Carbon\Carbon::parse($lesson->date)->format('d/m') }}</th>
                            @endforeach
                            <th class="sticky-top"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                            <tr>
                                <td>
                                    <input type="checkbox" class="student-checkbox" value="{{ $student->id }}">
                                    <button type="button" class="btn btn-link btn-sm" style="color: #285d8c;" data-bs-toggle="modal" data-bs-target="#editModal{{ $student->id }}" onclick="loadModalData({{ $student->id }})">
                                        <i class="bi bi-pencil-square"></i> Sửa
                                    </button>

                                    <div class="modal fade" id="editModal{{ $student->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editModalLabel">Chỉnh sửa thông tin</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('student.update', $student->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="form-group row">
                                                            <div class="col-md-6 mb-3">
                                                                <label for="surname" class="form-label">Họ</label>
                                                                <input type="text" class="form-control" id="surname" name="surname" placeholder="Nhập họ sinh viên" value="{{ $student->surname }}" required>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label for="forename" class="form-label">Tên</label>
                                                                <input type="text" class="form-control" id="forename" name="forename" placeholder="Nhập tên sinh viên" value="{{ $student->forename }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="id" class="form-label">Mã số sinh viên</label>
                                                            <input type="text" class="form-control" id="id" name="id" placeholder="Nhập mã số sinh viên" value="{{ $student->id }}" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="email" class="col-sm-3 col-form-label">Email</label>
                                                            <input type="text" class="form-control" id="email" name="email" placeholder="Nhập email sinh viên" value="{{ $student->email }}">
                                                        </div>
                                                        @if (\App\Models\Attendance::where('student', $student->id)->exists())
                                                            <div class="form-group row mt-3">
                                                                <div class="col-md-6 mb-3">
                                                                    <label for="lessonSelect{{ $student->id }}" class="form-label">Buổi điểm danh</label>
                                                                    <select name="lesson" class="form-control" id="lessonSelect{{ $student->id }}" required>
                                                                        @foreach ($attendances as $attendance)
                                                                            @if ($attendance->student == $student->id)
                                                                                <option value="{{ $attendance->lesson }}" data-status="{{ $attendance->status }}">
                                                                                    {{ \Carbon\Carbon::parse(DB::table('lessons')->where('id', $attendance->lesson)->first()->date)->format('d/m/Y') }}
                                                                                    {{ \Carbon\Carbon::parse(DB::table('lessons')->where('id', $attendance->lesson)->first()->created_at)->format('H:i') }}
                                                                                </option>
                                                                            @endif    
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label for="statusSelect{{ $student->id }}" class="form-label">&nbsp;</label>
                                                                    <select name="status" class="form-control" id="statusSelect{{ $student->id }}" required>
                                                                        <option value="có">Có</option>
                                                                        <option value="vắng">Vắng</option>
                                                                        <option value="trễ">Trễ</option>
                                                                    </select>
                                                                </div>   
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="button" class="btn btn-link btn-sm" style="color: #285d8c;" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $student->id }}">
                                        <i class="bi bi-trash"></i> Xóa
                                    </button>

                                    <div class="modal fade" id="deleteModal{{ $student->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $student->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel{{ $student->id }}">Xác nhận xóa</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Bạn có chắc chắn muốn xóa sinh viên <strong>{{ $student->surname }} {{ $student->forename }} - {{ $student->id }}</strong> không?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                                    <form action="{{ route('student.destroy', $student->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Xóa</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $student->surname }} {{ $student->forename }}</td>
                                <td>{{ $student->id }}</td>
                                @foreach ($attendances as $attendance)
                                    @if ($attendance->student == $student->id)
                                        <td class="{{ $attendance->status == 'vắng' ? 'text-danger' : ($attendance->status == 'trễ' ? 'text-warning' : '') }}" 
                                            @if($attendance->datetime)
                                                data-bs-toggle="tooltip" 
                                                data-bs-placement="top" 
                                                title="{{ \Carbon\Carbon::parse($attendance->datetime)->format('d/m/y H:i') }}"
                                            @endif>
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
            function loadModalData(studentId) {
                const lessonSelect = document.getElementById(`lessonSelect${studentId}`);
                const statusSelect = document.getElementById(`statusSelect${studentId}`);
                
                statusSelect.selectedIndex = 0;

                const selectedOption = lessonSelect.options[lessonSelect.selectedIndex];
                const statusValue = selectedOption.getAttribute('data-status');

                for (let i = 0; i < statusSelect.options.length; i++) {
                    if (statusSelect.options[i].value === statusValue) {
                        statusSelect.selectedIndex = i;
                        break;
                    }
                }
            }

            document.querySelectorAll('[id^="lessonSelect"]').forEach(select => {
                select.addEventListener('change', function() {
                    const studentId = this.id.replace('lessonSelect', '');
                    const statusSelect = document.getElementById(`statusSelect${studentId}`);
                    const selectedOption = this.options[this.selectedIndex];
                    const statusValue = selectedOption.getAttribute('data-status');

                    for (let i = 0; i < statusSelect.options.length; i++) {
                        if (statusSelect.options[i].value === statusValue) {
                            statusSelect.selectedIndex = i;
                            break;
                        }
                    }
                });
            });

            document.getElementById('deleteAllModal').addEventListener('show.bs.modal', function () {
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

            $(document).ready(function() {
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            });
        </script>
    </div>
@endsection