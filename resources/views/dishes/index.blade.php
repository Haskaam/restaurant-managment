@extends('layouts.app')

@section('title', 'Dania')

@section('content')

<div class="page-header">
    <div>
        <h2>Dania</h2>
        <p>Zarządzaj pozycjami w menu.</p>
    </div>

    <a href="{{ route('dishes.create') }}" class="btn btn-primary">
        Dodaj danie
    </a>
</div>

<form action="{{ route('dishes.index') }}" method="GET">
    <input
        type="text"
        name="search"
        value="{{ request('search') }}"
        placeholder="Wyszukaj danie..."
    >

    <input type="hidden" name="sort" value="{{ $sort }}">
    <input type="hidden" name="direction" value="{{ $direction }}">

    <button type="submit" class="btn btn-primary">
        Szukaj
    </button>
</form>

<div class="table-wrapper">

    <table class="data-table">
        <thead>
            <tr>
                <th>
                    <a href="{{route('dishes.index', [
                        'search' => request('search'),
                        'sort' => 'name',
                        'direction' => $sort === 'name' && $direction === 'asc'
                            ? 'desc'
                            :'asc'
                    ])}}">
                    Nazwa
                    </a>
                </th>

                <th>
                    <a href="{{route('dishes.index', [
                        'search' => request('search'),
                        'sort' => 'name',
                        'direction' => $sort === 'name' && $direction === 'asc'
                            ? 'desc'
                            :'asc'
                    ])}}">
                    Kategoria
                    </a>
                </th>

                <th>
                    <a href="{{route('dishes.index', [
                        'search' => request('search'),
                        'sort' => 'net_price',
                        'direction' => $sort === 'net_price' && $direction === 'asc'
                            ? 'desc'
                            :'asc'
                    ])}}">
                    Cena netto
                    </a>
                </th>

                <th>
                    <a href="{{route('dishes.index', [
                        'search' => request('search'),
                        'sort' => 'vat_rate',
                        'direction' => $sort === 'vat_rate' && $direction === 'asc'
                            ? 'desc'
                            :'asc'
                    ])}}">
                    VAT
                    </a>
                </th>

                <th>
                    <a href="{{route('dishes.index', [
                        'search' => request('search'),
                        'sort' => 'is_available',
                        'direction' => $sort === 'is_available' && $direction === 'asc'
                            ? 'desc'
                            :'asc'
                    ])}}">
                    Dostępność
                    </a>
                </th>
            </tr>
        </thead>

        <tbody>
            @foreach($dishes as $dish)
                <tr>
                    <td>{{ $dish->name }}</td>

                    <td>
                        {{ $dish->category->name }}
                    </td>

                    <td>
                        {{ number_format($dish->net_price, 2) }} zł
                    </td>

                    <td>
                        {{ number_format($dish->vat_rate, 2) }}%
                    </td>

                    <td>
                        <span class="{{ $dish->is_available ? 'status status-active' : 'status status-inactive' }}">
                            {{ $dish->is_available ? 'Dostępne' : 'Niedostępne' }}
                        </span>
                    </td>

                    <td>
                        <a
                            href="{{ route('dishes.edit', $dish) }}"
                            class="btn btn-secondary"
                        >
                            Edytuj
                        </a>

                        <form
                            action="{{ route('dishes.availability', $dish) }}"
                            method="POST"
                            style="display: inline;"
                        >
                            @csrf
                            @method('PATCH')

                            <button
                                type="submit"
                                class="{{ $dish->is_available ? 'btn btn-danger' : 'btn btn-primary' }}"
                            >
                                {{ $dish->is_available ? 'Wyłącz' : 'Włącz' }}
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>

@endsection
