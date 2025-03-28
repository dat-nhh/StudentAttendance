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
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</head>
<body>
    @if (Route::has('login'))
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
        @auth
            <div class="container-fluid">
                <ul class="navbar-nav me-auto mx-5">
                    <li class="nav-item active">
                        <a class="nav-link" href="{{ route('class.index') }}">Danh sách lớp học</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto mx-5">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <strong>{{ Auth::user()->name }}</strong>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink">
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">Thông tin người dùng</a>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item">Đăng xuất</button>
                            </form>
                        </div>
                    </li>
                </ul>
            @else
                <ul class="navbar-nav ms-auto mx-5">
                    <li class="nav-item active">
                        <a class="nav-link" href="{{ route('login') }}">Đăng nhập</a>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item active">
                            <a class="nav-link" href="{{ route('register') }}">Đăng ký</a>
                        </li>
                    @endif                  
                </ul>
            @endauth
        </nav>
    @endif
    <div class="container-fluid">
        <div class="row">
            <main class="p-4">
                @session('message')
                    <div id="alert" class="alert alert-success" role="alert"> {{ $value }} </div>
                @endsession

                @session('error')
                    <div id="alert" class="alert alert-danger" role="alert"> {{ $value }} </div>
                @endsession

                <script>
                    $(document).ready(function() {
                        setTimeout(function() {
                            $('#alert').fadeOut(1000);
                        }, 2000);
                    });
                </script>
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>