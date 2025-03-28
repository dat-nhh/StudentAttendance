<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Điểm Danh Sinh Viên</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>
@php
    $currentTime = now(); 
    $status = 'vắng';
    
    $lessonTime = \Carbon\Carbon::parse($time); 

    if ($currentTime->toDateString() == $lesson->date) {
        if ($currentTime->lessThanOrEqualTo($lessonTime->copy()->addMinutes(5))) {
            $status = 'có';
        } elseif ($currentTime->lessThanOrEqualTo($lessonTime->copy()->addMinutes(60))) {
            $status = 'trễ';
        }
    }
@endphp

<div class="container-fluid">
    <div class="row justify-content-center">
        <main class="p-4">
            @session('message')
                <div id="alert" class="alert alert-success col-md-4 mx-auto" role="alert"> {{ session('message') }} </div>
            @endsession

            @session('error')
                <div id="alert" class="alert alert-danger col-md-4 mx-auto" role="alert"> {{ session('error') }} </div>
            @endsession

            <script>
                $(document).ready(function() {
                    setTimeout(function() {
                        $('#alert').fadeOut(1000);
                    }, 2000);
                });
            </script>

            <div class="card col-md-4 mx-auto">
                <h2 class="card-header text-center">Nhập Mã Số Sinh Viên</h2>
                <div class="card-body">
                    <form action="{{ route('form.update', \Illuminate\Support\Facades\Crypt::encrypt($lesson->id)) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <input type="text" class="form-control" name="student" placeholder="Nhập MSSV" required>
                            <input type="hidden" name="status" value="{{ $status }}">
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">Gửi</button>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

</body>
</html>