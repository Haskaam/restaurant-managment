@extends('layouts.app')

@section('title', 'Edytuj danie')

@section('content')

<div class="page-header">
    <h2>Edytuj danie</h2>
    <p>Zmień dane wybranej pozycji menu.</p>
</div>

<div class="form-card">

    <form action="{{ route('dishes.update', $dish) }}" method="POST">
        @csrf
        @method('PATCH')

        <div class="form-group">
            <label for="name">Nazwa</label>

            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name', $dish->name) }}"
            >

            @error('name')
                <p class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="description">Opis</label>

            <textarea
                id="description"
                name="description"
            >{{ old('description', $dish->description) }}</textarea>

            @error('description')
                <p class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="category_id">Kategoria</label>

            <select id="category_id" name="category_id">
                @foreach($categories as $category)
                    <option
                        value="{{ $category->id }}"
                        @selected(
                            old('category_id', $dish->category_id) == $category->id
                        )
                    >
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            @error('category_id')
                <p class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="net_price">Cena netto</label>

            <input
                type="number"
                step="0.01"
                id="net_price"
                name="net_price"
                value="{{ old('net_price', $dish->net_price) }}"
            >

            @error('net_price')
                <p class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="vat_rate">VAT (%)</label>

            <input
                type="number"
                step="0.01"
                id="vat_rate"
                name="vat_rate"
                value="{{ old('vat_rate', $dish->vat_rate) }}"
            >

            @error('vat_rate')
                <p class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">
            Zapisz zmiany
        </button>
    </form>

</div>

@endsection
