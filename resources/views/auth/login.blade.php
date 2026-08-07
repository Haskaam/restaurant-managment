@extends('layouts.guest')

@section('title', 'Logowanie')

@section('content')

<div class="auth-card">

    <div class="login-header">
        <h1>Restaurant Management</h1>
        <p>Zaloguj się do panelu</p>
    </div>

    <form action="/login" method="POST">
        @csrf

        <div class="form-group">
            <label for="email">E-mail</label>

            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
            >

            @error('email')
                <p class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">Hasło</label>

            <input
                type="password"
                id="password"
                name="password"
            >
        </div>

        <button type="submit" class="btn btn-primary login-btn">
            Zaloguj się
        </button>
    </form>

</div>

@endsection
