@extends('layouts.app')

@section('title', 'Zamówienia')

@section('content')

<div class="page-header">
    <div>
        <h2>Zamówienia</h2>
        <p>Lista aktualnych zamówień.</p>
    </div>

    <a href="{{ route('orders.create') }}" class="btn btn-primary">
        Nowe zamówienie
    </a>
</div>

<div class="table-wrapper">
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Kelner</th>
                <th>Pozycje</th>
                <th>Status</th>
                <th>Kwota</th>
                <th>Przyjęte</th>
                <th>Akcje</th>
            </tr>
        </thead>

        <tbody>
            @foreach($orders as $order)
                <tr>
                    <td>#{{ $order->id }}</td>

                    <td>
                        {{ $order->waiter->first_name }}
                        {{ $order->waiter->last_name }}
                    </td>

                    <td>
                        @foreach($order->items as $item)
                        <div>
                            <strong>
                                {{ $item->quantity }}x {{ $item->dish->name }}
                            </strong>

                            @if($item->notes)
                            <span>
                                - {{ $item->notes }}
                            </span>
                            @endif
                        </div>
                        @endforeach
                    </td>

                    <td>
                        {{ $order->status }}
                    </td>

                    <td>
                        {{ number_format($order->total_gross, 2) }} zł
                    </td>

                    <td>
                        {{ $order->accepted_at }}
                    </td>

                    <td>
                    @if(
                        $order->status === 'ready'
                        && $order->waiter_id === Auth::id()
                    )
                        <form
                            action="{{ route('orders.collect', $order) }}"
                            method="POST"
                     >
                          @csrf
                         @method('PATCH')

                            <button type="submit" class="btn btn-primary">
                             Odbierz zamówienie
                         </button>
                        </form>
                    @endif

                    @if ($order->status === 'collected' && $order->waiter_id === Auth::id())

                    <a
                    href="{{ route('orders.payment', $order)}}"
                    class="btn btn-primary"
                    >
                        Zakmnij zamówienie
                    </a>
                    @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection
