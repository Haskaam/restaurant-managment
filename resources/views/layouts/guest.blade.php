<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Restaurant Management')</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body class="guest-body">

    <main class="guest-container">
        @yield('content')
    </main>

</body>
</html>
