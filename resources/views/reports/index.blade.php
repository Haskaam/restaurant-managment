@extends('layouts.app')

@section('title', 'Raport dzienny')

@section('content')

<div class="page-header">
    <div>
        <h2>Raport dzienny</h2>
        <p>Podsumowanie sprzedaży restauracji.</p>
    </div>
</div>


{{-- WYBÓR DATY --}}

<div class="form-card">

    <form action="{{ route('reports.index') }}" method="GET">

        <div class="form-group">
            <label for="date">Data raportu</label>

            <input
                type="date"
                name="date"
                id="date"
                value="{{ $date }}"
            >
        </div>

        <button type="submit" class="btn btn-primary">
            Pokaż raport
        </button>

    </form>

</div>


{{-- GŁÓWNE PODSUMOWANIE --}}

<div class="dashboard-grid">

    <div class="card">
        <h3>Sprzedaż brutto</h3>

        <strong>
            {{ number_format($totalGross, 2) }} zł
        </strong>
    </div>


    <div class="card">
        <h3>Sprzedaż netto</h3>

        <strong>
            {{ number_format($totalNet, 2) }} zł
        </strong>
    </div>


    <div class="card">
        <h3>VAT</h3>

        <strong>
            {{ number_format($totalVat, 2) }} zł
        </strong>
    </div>


    <div class="card">
        <h3>Rabaty</h3>

        <strong>
            {{ number_format($discounts, 2) }} zł
        </strong>
    </div>


    <div class="card">
        <h3>Liczba zamówień</h3>

        <strong>
            {{ $orderCount }}
        </strong>
    </div>

</div>


{{-- SZCZEGÓŁOWE STATYSTYKI --}}

<div class="dashboard-grid">

    {{-- METODY PŁATNOŚCI --}}

    <div class="card">

        <h3>Metody płatności</h3>

        @forelse($payments as $payment)

            <p>
                @if($payment->method === 'cash')
                    Gotówka
                @elseif($payment->method === 'card')
                    Karta / BLIK
                @elseif($payment->method === 'voucher')
                    Voucher
                @else
                    {{ ucfirst($payment->method) }}
                @endif

                —

                <strong>
                    {{ number_format($payment->total, 2) }} zł
                </strong>
            </p>

        @empty

            <p>Brak płatności w wybranym dniu.</p>

        @endforelse

    </div>


    {{-- NAJPOPULARNIEJSZE DANIA --}}

    <div class="card">

        <h3>Najpopularniejsze dania</h3>

        @forelse($topDishes as $dish)

            <p>
                {{ $dish->dish_name }}

                —

                <strong>
                    {{ $dish->total_quantity }} szt.
                </strong>
            </p>

        @empty

            <p>Brak sprzedanych dań w wybranym dniu.</p>

        @endforelse

    </div>


    {{-- SPRZEDAŻ KELNERÓW --}}

    <div class="card">

        <h3>Sprzedaż kelnerów</h3>

        @forelse($waiterSales as $row)

            <p>
                {{ $row->waiter->first_name }}
                {{ $row->waiter->last_name }}

                —

                <strong>
                    {{ number_format($row->total_sales, 2) }} zł
                </strong>

                ({{ $row->order_count }} zam.)
            </p>

        @empty

            <p>Brak sprzedaży w wybranym dniu.</p>

        @endforelse

    </div>

</div>

@endsection
