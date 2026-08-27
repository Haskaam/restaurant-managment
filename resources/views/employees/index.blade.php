@extends('layouts.app')

@section('title', 'Pracownicy')

@section('content')


    <a class="btn btn-primary" href="{{route('employees.create')}}">Dodaj pracownika</a>

<form action="{{ route('employees.index') }}" method="GET">

    @csrf

    <input
        type="text"
        name="search"
        value="{{ request('search') }}"
        placeholder="Szukaj pracownika..."
    >

    <input
        type="hidden"
        name="sort"
        value="{{ $sort }}"
    >

    <input
        type="hidden"
        name="direction"
        value="{{ $direction }}"
    >

    <button type="submit" class="btn btn-primary">
        Szukaj
    </button>

    @if(request('search'))
        <a
            href="{{ route('employees.index') }}"
            class="btn btn-secondary"
        >
            Wyczyść
        </a>
    @endif

</form>

    <div class="table-wrapper">
        <table class="data-table">
            <tr>
                <th>
                    <a href="{{route('employees.index', [
                        'search' => request('search'),
                        'sort' => 'first_name',
                        'direction' =>
                            $sort === 'first_name' && $direction === 'asc'
                                ? 'desc'
                                : 'asc'
                        ])}}">
                        Imię
                    </a>

                </th>

                <th>
                    <a href="{{route('employees.index', [
                        'search' => request('search'),
                        'sort' => 'last_name',
                        'direction' =>
                            $sort === 'last_name' && $direction === 'asc'
                                ? 'desc'
                                : 'asc'
                        ])}}">
                        Nazwisko
                    </a>

                </th>

                <th>Dział</th>

                <th>Rola</th>

                <th>
                    <a href="{{route('employees.index', [
                        'search' => request('search'),
                        'sort' => 'email',
                        'direction' =>
                            $sort === 'email' && $direction === 'asc'
                                ? 'desc'
                                : 'asc'
                        ])}}">
                        Email
                    </a>

                </th>

                <th>
                    <a href="{{route('employees.index', [
                        'search' => request('search'),
                        'sort' => 'is_active',
                        'direction' =>
                            $sort === 'is_active' && $direction === 'asc'
                                ? 'desc'
                                : 'asc'
                        ])}}">
                        Status
                    </a>

                </th>

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
