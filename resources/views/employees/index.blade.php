@extends('layouts.app')

@section('title', 'Pracownicy')

@section('content')


    <a class="btn btn-primary" href="{{route('employees.create')}}">Dodaj pracownika</a>

    <div class="table-wrapper">
        <table class="data-table">
            <tr>
                <th>Imię</th>
                <th>Nazwisko</th>
                <th>Dział</th>
                <th>Rola</th>
                <th>Email</th>
                <th>Status</th>
                <th>Akcje</th>
            </tr>
            <tbody>
            @foreach($employees as $user)
            <tr>
                <td>{{$user->first_name}}</td>
                <td>{{$user->last_name}}</td>
                <td>{{$user->department->name}}</td>
                <td>
                    @foreach($user->roles as $role)
                    {{$role->name}}@if(! $loop->last), @endif
                    @endforeach
                </td>
                <td>{{$user->email}}</td>

                <td>
                    <span class="{{$user->is_active ? 'status status-active' : 'status status-inactive'}}">
                        {{$user->is_active ? 'Aktywny' : 'Nieaktywny'}}
                    </span>
                </td>

                <td>
                    <a class="btn btn-primary" href="{{route('employees.edit', $user)}}">
                        Edytuj
                    </a>

                    @if(! $user->hasRole('director') && $user->is_active)
                    <form action="{{route('employees.terminate', $user)}}" method="POST">
                        @csrf
                        @method('PATCH')

                        <button class="btn btn-danger" type="submit"
                        onclick="return confirm('Czy na pewno chcesz zwolnić {{$user->first_name}} {{$user->last_name}}?')">
                            Zwolnij
                        </button>
                    </form>
                    @endif
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
