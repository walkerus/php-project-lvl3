<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>@yield('title')</title>
    <script src="{{ empty($_SERVER['HTTPS']) ? asset('js/app.js') : secure_asset('js/app.js') }}" defer></script>
    <link href="{{ empty($_SERVER['HTTPS']) ? asset('css/app.css') : secure_asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<header class="flex-shrink-0">
    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
        <a class="navbar-brand" href="/">Анализатор страниц</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" href="/">Главная</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " href="{{ route('urls.index') }}">Сайты</a>
                </li>
            </ul>
        </div>
    </nav>
</header>

@include('flash')

<main class="flex-grow-1">
    @yield('content')
</main>

<footer class="border-top py-3 mt-5 flex-shrink-0">
    <div class="container-lg">
        <div class="text-center">
            <a href="https://hexlet.io/pages/about" target="_blank">Hexlet</a>
        </div>
    </div>
</footer>

</body>
</html>
