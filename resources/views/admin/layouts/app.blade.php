<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Responsive Admin Dashboard Template">
        <meta name="keywords" content="admin,dashboard">
        <meta name="author" content="stacks">
        @yield('title')
        <link href="public/https://fonts.googleapis.com/css?family=Poppins:400,500,700,800&display=swap') }}" rel="stylesheet">
        <link href="{{ url('public/assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ url('public/assets/plugins/font-awesome/css/all.min.css') }}" rel="stylesheet">
        <link href="{{ url('public/assets/plugins/perfectscroll/perfect-scrollbar.css') }}" rel="stylesheet">
        <link href="{{ url('public/assets/plugins/apexcharts/apexcharts.css') }}" rel="stylesheet">
        @yield('css')
        <link href="{{ url('public/assets/css/main.min.css') }}" rel="stylesheet">
        <link href="{{ url('public/assets/css/custom.css') }}" rel="stylesheet">
        <style>
            .w-full{
                position: absolute;
                bottom: 10px;
            }
        </style>
    </head>
    <body>
        <div class="page-container">
            @include('admin.layouts.header')
            @include('admin.layouts.sidebar')
            <div class="page-content">
                <div class="main-wrapper">
                    @yield('content')
                </div>
            </div>
        </div>
        <!-- Javascripts -->
        <script src="{{ url('public/assets/plugins/jquery/jquery-3.4.1.min.js') }}"></script>
        <script src="public/https://unpkg.com/@popperjs/core@2"></script>
        <script src="{{ url('public/assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
        <script src="public/https://unpkg.com/feather-icons"></script>
        <script src="{{ url('public/assets/plugins/perfectscroll/perfect-scrollbar.min.js') }}"></script>
        <script src="{{ url('public/assets/plugins/apexcharts/apexcharts.min.js') }}"></script>
        <script src="{{ url('public/assets/js/main.min.js') }}"></script>
    </body>
</html>
