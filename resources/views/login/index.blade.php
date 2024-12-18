<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Login</title>
</head>

<body>
    <div class="wrapper">
        <div class="container main">
            <div class="row">
                <div class="col-md-6 side-image">
                </div>
                <div class="col-md-6 right">
                    <div class="input-box">
                        <header>Silakan Login</header>
                        <form action="{{ route('login') }}" method="POST">
                            @csrf
                            <div class="input-field">
                                <input type="text" class="input" id="username" name="username" required>
                                <label for="email">Username</label>
                            </div>
                            <div class="input-field">
                                <input type="password" class="input" id="pass" name="password" required>
                                <label for="pass">Password</label>
                            </div>
                            <div class="input-field">
                                <input type="submit" class="submit" value="Log In">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
@if ($message = Session::get('LoginError'))
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Login Gagal",
            });
        });
    </script>
@endif

</html>
