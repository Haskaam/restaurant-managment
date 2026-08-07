@extends('layouts.app')

@section('title', 'Pracownicy')

@section('content')

<div class="auth-card">
        <h2>Dodaj pracownika</h2>

        <form action="/employees" method="POST">
            @csrf

            <div class="form-group">
                <label for="first_name">Imię</label>

                <input type="text" id="first_name" name="first_name">

                @error('first_name')
                    <p class="field-error">
                        {{$message}}
                    </p>
                @enderror
            </div>

            <div class="form-group">
                <label for="last_name">Nazwisko</label>
                <input type="text" id="last_name" name="last_name">

                @error('last_name')
                    <p class="field-error">
                        {{$message}}
                    </p>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email">

                @error('email')
                    <p class="field-error">
                        {{$message}}
                    </p>
                @enderror
            </div>

            <div class="form-group">
                <label for="department_id">Dział</label>

                <select id="department_id" name="department_id">
                    @foreach ($departments as $department)
                    <option value="{{$department->id}}">{{$department->name}}</option>
                    @endforeach
                </select>

                @error('department_id')
                    <p class="field-error">
                        {{$message}}
                    </p>
                @enderror
            </div>

            <div class="form-group">
                <label for="role_id">Rola</label>

                <select id="role_id" name="role_id">
                    @foreach ($roles as $role)
                    <option value="{{$role->id}}">{{$role->name}}</option>
                    @endforeach
                </select>

                @error('role_id')
                    <p class="field-error">
                        {{$message}}
                    </p>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Hasło tymczasowe</label>
                <input type="password" id="password" name="password">

                @error('password')
                    <p class="field-error">
                        {{$message}}
                    </p>
                @enderror
            </div>

            <button class="btn btn-primary login-btn" type="submit">Dodaj pracownika</button>
        </form>
</div>
@endsection
