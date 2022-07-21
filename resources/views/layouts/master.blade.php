<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Job Finder</title>

    {!! Html::style('css/style.css') !!}
</head>
<body>
    @include('partials.navbar')

    <div class="container">
        @yield('content')
    </div>

    @include('partials.footer')

{!! Html::script('js/script.js') !!}
</body>
</html>