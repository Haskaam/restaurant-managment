<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Restaurant Management')</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>

<div class="app-layout">

    @auth
        <aside class="sidebar">
            <div class="sidebar-brand">
                <h1>Restaurant</h1>
            </div>

            <nav class="sidebar-nav">

                <a href="{{ route('dashboard') }}">
                    Dashboard
                </a>

                @if(Auth::user()->hasRole('director'))
                    <a href="{{ route('employees.index') }}">
                        Pracownicy
                    </a>

                    <a href="{{ route('employees.create') }}">
                        Dodaj pracownika
                    </a>
                @endif

                <a href="{{ route('account.password.edit') }}">
                    Zmień hasło
                </a>

            </nav>
        </aside>
    @endauth


    <div class="main-area">

        @auth
            <header class="topbar">

                <div>
                    <span class="user-name">
                        {{ Auth::user()->first_name }}
                        {{ Auth::user()->last_name }}
                    </span>
                </div>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf

                    <button type="submit" class="logout-btn">
                        Wyloguj się
                    </button>
                </form>

            </header>
        @endauth


        <main class="content">

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
            <div class="alert alert-error">
                {{session('error')}}
            </div>
            @endif

            @yield('content')

        </main>

    </div>

</div>

</body>
</html>
