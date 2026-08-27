@extends('layouts.app')

@section('title', 'Dodaj danie')

@section('content')

<div class="page-header">
    <h2>Dodaj danie</h2>
    <p>Dodaj nową pozycję do menu.</p>
</div>

<div class="form-card">

    <form action="{{ route('dishes.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="name">Nazwa</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}">

            @error('name')
                <p class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="description">Opis</label>
            <textarea id="description" name="description">{{ old('description') }}</textarea>

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
                        @selected(old('category_id') == $category->id)
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
                value="{{ old('net_price') }}"
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
                value="{{ old('vat_rate') }}"
            >

            @error('vat_rate')
                <p class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">
            Dodaj danie
        </button>
    </form>

</div>

@endsection
