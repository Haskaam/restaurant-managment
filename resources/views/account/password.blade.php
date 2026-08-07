@extends('layouts.guest')

@section('title', 'Logowanie')

@section('content')


    <div class="auth-card">

        <div class="login-header">
            <h1>Zmiana hasła</h1>
            <p>Ustaw nowe hasło aby kontynuwować</p>
        </div>


        <form action="{{route('account.password.update')}}" method="POST">
            @csrf
            @method('PATCH')


            <div class="form-group">
                <label for="current_password">Aktualne hasło</label>
                <input type="password" name="current_password" id="current_password">

                @error('current_password')
                    <p class="field-error">
                        {{$message}}
                    </p>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Nowe hasło</label>
                <input type="password" name="password" id="password">

                @error('password')
                    <p class="field-error">
                        {{$message}}
                    </p>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Powtórz nowe hasło</label>
                <input type="password" name="password_confirmation" id="password_confirmation">

                @error('password_confirmation')
                    <p class="field-error">
                        {{$message}}
                    </p>
                @enderror
            </div>

            <button class="btn btn-primary login-btn" type="submit">Zmień hasło</button>
        </form>
    </div>
@endsection
