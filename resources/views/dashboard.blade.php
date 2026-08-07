@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="page-header">
    <div>
        <h2>Dashboard</h2>
        <p>Witaj, {{ $user->first_name }} {{ $user->last_name }}</p>
    </div>
</div>

<div class="dashboard-grid">

    <div class="card">
        <h3>Dział</h3>
        <p>{{ $user->department->name }}</p>
    </div>

    <div class="card">
        <h3>Rola</h3>

        @foreach($user->roles as $role)
            <span class="badge">
                {{ $role->name }}
            </span>
        @endforeach
    </div>

</div>

@endsection
