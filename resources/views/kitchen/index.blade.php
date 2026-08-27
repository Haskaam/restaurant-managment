@extends('layouts.app')

@section('title', 'Kuchnia')

@section('content')

<div class="page-header">
    <h2>Kuchnia</h2>
    <p>Aktywne zamówienia do przygotowania.</p>
</div>

<div class="dashboard-grid">

    @foreach($orders as $order)

    <div class="card">

        <h3>Zamówienie #{{ $order->id }}</h3>

        <p>
            Kelner:
            {{ $order->waiter->first_name }}
            {{ $order->waiter->last_name }}
        </p>

        <p>Status: {{ $order->status }}</p>

        @foreach($order->items as $item)
            <div>
                <strong>
                    {{ $item->quantity }}x {{ $item->dish_name }}
                </strong>

                @if($item->notes)
                    <p>Uwagi: {{ $item->notes }}</p>
                @endif
            </div>
        @endforeach


        @if($order->status === 'accepted')

            <form
                action="{{ route('kitchen.orders.start', $order) }}"
                method="POST"
            >
                @csrf
                @method('PATCH')

                <button type="submit" class="btn btn-primary">
                    Rozpocznij przygotowanie
                </button>
            </form>

        @endif


        @if($order->status === 'in_preparation')

            <form
                action="{{ route('kitchen.orders.ready', $order) }}"
                method="POST"
            >
                @csrf
                @method('PATCH')

                <button type="submit" class="btn btn-primary">
                    Oznacz jako gotowe
                </button>
            </form>

        @endif

    </div>

@endforeach

</div>

@endsection
