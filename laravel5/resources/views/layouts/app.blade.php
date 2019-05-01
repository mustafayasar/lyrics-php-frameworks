
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Lyrics')</title>
    <meta name="csrf-param" content="_csrf-frontend">
    <meta name="csrf-token" content="WaDZEk0YrTwCV4MQWKXiX-Ih7QBLkNSxpfIEdDcFe_0WlpBVNyHaTToQ1XFgnYYPrEKvXwe9o4LkqEANDjwrpA==">

    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/clean-blog.css') }}" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
</head>
<body>



<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNav">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}" title="Lyrics">Lyrics</a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            Menu
            <i class="fas fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('random_lyrics') }}">Random Lyrics</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('singers', 'hit') }}">Singers</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('songs', 'hit') }}">Songs</a>
                </li>
            </ul>
            <form class="form-inline my-2 my-lg-0" action="{{ route('search') }}" method="get">
                <input class="form-control mr-sm-2" name="q" type="text" placeholder="Search" aria-label="Search">
            </form>
        </div>
    </div>
</nav>

<!-- Page Header -->
<header class="masthead" style="background-image: url('/img/bg-<?= rand(1, 7)?>.jpg')">
    <div class="overlay"></div>
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-10 mx-auto">
                <div class="site-heading">
                    <h1>@yield('title')</h1>

                    @hasSection('description')
                        <span class="subheading">@yield('description')</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Main Content -->
<div class="container">
    <div class="row">
        @yield('content')
    </div>
</div>

<hr>

<!-- Footer -->
<footer>
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-10 mx-auto">
                <p class="copyright text-muted">Copyright &copy; Lyrics Project 2019</p>
            </div>
        </div>
    </div>
</footer>
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/clean-blog.min.js') }}"></script></body>
</body>
</html>