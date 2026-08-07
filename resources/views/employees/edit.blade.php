@extends('layouts.app')

@section('title', 'Pracownicy')

@section('content')


<div class="auth-card">

        <form action="{{route('employees.update', $user)}}" method="POST">
            @csrf
            @method('PATCH')


            <div class="form-group">
            <label for="first_name">Imię</label>
            <input type="text" id="first_name" name="first_name" value="{{$user->first_name}}">
            </div>


            <div class="form-group">
            <label for="last_name">Nazwisko</label>
            <input type="text" id="last_name" name="last_name" value="{{$user->last_name}}">
            </div>

            <div class="form-group">
            <label for="department">Dział</label>
            <select id="department" name="department">
                @foreach($departments as $department)

                <option value="{{$department->id}}">{{$department->name}}</option>

                @endforeach
            </select>
            </div>

            <div class="form-group">
            <label for="role">Rola</label>
            <select id="role" name="role">
                @foreach($roles as $role)

                <option value="{{$role->id}}">{{$role->name}}</option>

                @endforeach
            </select>
            </div>

            <button class="btn btn-primary login-btn" type="submit">Zapisz zmiany</button>
        </form>
</div>
@endsection
