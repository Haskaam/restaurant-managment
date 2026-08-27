@extends('layouts.app')

@section('title', 'Rozliczenie zamówienia')

@section('content')

<div class="page-header">
    <div>
        <h2>Rozliczenie zamówienia #{{ $order->id }}</h2>
        <p>
            Kelner:
            {{ $order->waiter->first_name }}
            {{ $order->waiter->last_name }}
        </p>
    </div>

    <a href="{{ route('orders.index') }}" class="btn btn-secondary">
        Wróć
    </a>
</div>


<div class="dashboard-grid">

    <div class="card">

        <h3>Podsumowanie</h3>

        @foreach($order->items as $item)
            <div>
                {{ $item->quantity }}x {{ $item->dish_name }}

                <strong>
                    {{ number_format($item->total_gross, 2) }} zł
                </strong>
            </div>
        @endforeach

        <hr>

        <p>
            Do zapłaty:
            <strong>
                {{ number_format($order->total_gross, 2) }} zł
            </strong>
        </p>

    </div>


    <div class="card">

        <h3>Płatności</h3>

        @php
            $paid = $order->payments->sum('amount');
            $remaining = max(0, $order->total_gross - $paid);
        @endphp

        @if($order->payments->isNotEmpty())

            @foreach($order->payments as $payment)
                <p>
                    {{ ucfirst($payment->method) }}
                    —
                    {{ number_format($payment->amount, 2) }} zł
                </p>
            @endforeach

        @else
            <p>Brak dodanych płatności.</p>
        @endif

        <hr>

        <p>
            Zapłacono:
            <strong>
                {{ number_format($paid, 2) }} zł
            </strong>
        </p>

        <p>
            Pozostało:
            <strong>
                {{ number_format($remaining, 2) }} zł
            </strong>
        </p>

    </div>

</div>

@if($order->payments->isEmpty())

    <div class="form-card">

        <h3>Rabat</h3>

        <form
            action="{{ route('orders.discount', $order) }}"
            method="POST"
        >
            @csrf
            @method('PATCH')

            <div class="form-group">

                <label for="discount_percent">
                    Rabat (%)
                </label>

                <input
                    type="number"
                    name="discount_percent"
                    id="discount_percent"
                    min="0"
                    max="100"
                    step="1"
                    value="{{ old(
                        'discount_percent',
                        $order->discount_percent
                    ) }}"
                >

            </div>

            <div class="form-group">

                <label for="discount_reason">
                    Powód rabatu
                </label>

                <input
                    type="text"
                    name="discount_reason"
                    id="discount_reason"
                    value="{{ old(
                        'discount_reason',
                        $order->discount_reason
                    ) }}"
                    placeholder="Powód"
                >

            </div>

            <button
                type="submit"
                class="btn btn-secondary"
            >
                Zastosuj rabat
            </button>

        </form>

    </div>

@endif


@if($remaining > 0)

    <div class="form-card">

        <h3>Dodaj płatność</h3>

        <form
            action="{{ route('orders.payment.store', $order) }}"
            method="POST"
        >
            @csrf

            <div class="form-group">

                <label for="method">
                    Metoda płatności
                </label>

                <select name="method" id="method">

                    <option value="cash">
                        Gotówka
                    </option>

                    <option value="card">
                        Karta / BLIK
                    </option>

                    <option value="voucher">
                        Voucher
                    </option>

                </select>

                @error('method')
                    <p class="field-error">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            <div class="form-group">

                <label for="amount">
                    Kwota
                </label>

                <input
                    type="number"
                    name="amount"
                    id="amount"
                    step="0.01"
                    min="0.01"
                    max="{{ $remaining }}"
                    value="{{ old('amount', $remaining) }}"
                >

                @error('amount')
                    <p class="field-error">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            <button type="submit" class="btn btn-primary">
                Dodaj płatność
            </button>

        </form>

    </div>

@endif

@endsection
