<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Responsive Admin Dashboard Template">
        <meta name="keywords" content="admin,dashboard">
        <meta name="author" content="stacks">
        <title>Marriage - Login</title>
        <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,700,800&display=swap" rel="stylesheet">
        <link href="{{ url('public/assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ url('public/assets/plugins/font-awesome/css/all.min.css') }}" rel="stylesheet">
        <link href="{{ url('public/assets/plugins/perfectscroll/perfect-scrollbar.css') }}" rel="stylesheet">
        <link href="{{ url('public/assets/css/main.min.css') }}" rel="stylesheet">
        <link href="{{ url('public/assets/css/custom.css') }}" rel="stylesheet">
    </head>
    <body class="login-page">
        <div class='loader'>
            <div class='spinner-grow text-primary' role='status'>
              <span class='sr-only'>Loading...</span>
            </div>
          </div>
        <div class="container">
            <div class="row justify-content-md-center">
                <div class="col-md-12 col-lg-4">
                    <div class="card login-box-container">
                        <div class="card-body">
                            <div class="authent-logo">
                                <img src="{{ url('assets/images/logo@2x.png') }}" alt="">
                            </div>
                            <div class="authent-text">
                                <p>Welcome to Marriage</p>
                                <p>Please Sign-in to your account.</p>
                            </div>
                            <form action="{{ route('login.custom') }}" method="post">
                                @csrf
                                @if(Session::has('success'))
                                    <div class="alert alert-success">
                                        {{ Session::get('success') }}
                                        @php
                                            Session::forget('success');
                                        @endphp
                                    </div>
                                @endif
                                <div class="mb-3">
                                    <div class="form-floating">
                                        <input type="email" name="email" class="form-control" id="floatingInput" placeholder="name@example.com">
                                        <label for="floatingInput">Email address</label>
                                        @if ($errors->has('email'))
                                            <span class="text-danger">{{ $errors->first('email') }}</span>
                                        @endif
                                      </div>
                                </div>
                                <div class="mb-3">
                                    <div class="form-floating">
                                        <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Password">
                                        <label for="floatingPassword">Password</label>
                                        @if ($errors->has('password'))
                                            <span class="text-danger">{{ $errors->first('password') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-info m-b-xs">Sign In</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="{{ url('public/assets/plugins/jquery/jquery-3.4.1.min.js') }}"></script>
        <script src="public/https://unpkg.com/@popperjs/core@2"></script>
        <script src="{{ url('public/assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
        <script src="public/https://unpkg.com/feather-icons"></script>
        <script src="{{ url('public/assets/plugins/perfectscroll/perfect-scrollbar.min.js') }}"></script>
        <script src="{{ url('public/assets/js/main.min.js') }}"></script>
    </body>
</html>

