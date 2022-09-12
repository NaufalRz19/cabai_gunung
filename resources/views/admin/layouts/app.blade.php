<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>@yield('title') | Cabai Gunung</title>
    <link rel="shortcut icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">

    @include('admin.layouts.css')
    @yield('styles')

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">
    @vite(['resources/js/app.js'])
</head>

<body>
    <div id="app">
        <div class="main-wrapper">
            <div class="navbar-bg" style="background-color: #37966F !important;"></div>
            @include('admin.layouts.header')
            <div class="main-sidebar">
                @include('admin.layouts.sidebar')
            </div>

            <!-- Main Content -->
            <div class="main-content" style="padding-top: 150px !important;">
                <section class="section">
                    @yield('content')
                </section>
            </div>
            @include('admin.layouts.footer')
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="modalLogout" tabindex="-1" role="dialog" aria-labelledby="modalLogoutLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLogoutLabel">Konfirmasi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('logout') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        Apakah anda ingin <i>logout</i> dari aplikasi?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Logout</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- General JS Scripts -->
    @include('admin.layouts.js')
    <!-- Page Specific JS File -->
    @yield('scripts')
    <script>
    $('#btnLogout').on('click', function(){
        $('#modalLogout').modal('show');
        $('.modal-backdrop').removeClass('modal-backdrop');
    });
    </script>
</body>

</html>
