<?php

namespace App\Http\Controllers;

use App\Models\Order;

class KitchenController extends Controller
{
    public function index()
    {
        $orders = Order::with(['waiter', 'items'])
            ->whereIn('status', [
                'accepted',
                'in_preparation',
                'ready',
            ])
            ->orderBy('accepted_at')
            ->get();

        return view('kitchen.index', compact('orders'));
    }

    public function startPreparation(Order $order)
    {
        if ($order->status !== 'accepted') {
            return back()->with('error', 'Nie można rozpocząć przygotowania tego zamówienia.');
        }

        $oldStatus = $order->status;

        $order->update([
            'status' => 'in_preparation',
            'preparation_started_at' => now(),
        ]);

        $order->statusHistory()->create([
            'changed_by' => request()->user()->id,
            'from_status' => $oldStatus,
            'to_status' => 'in_preparation',
        ]);

        return back()->with('success', 'Rozpoczęto przygotowanie zamówienia.');
    }

    public function markReady(Order $order)
    {
        if ($order->status !== 'in_preparation') {
            return back()->with('error', 'To zamówienie nie jest aktualnie przygotowywane.');
        }

        $oldStatus = $order->status;

        $order->update([
            'status' => 'ready',
            'ready_at' => now(),
        ]);

        $order->statusHistory()->create([
            'changed_by' => request()->user()->id,
            'from_status' => $oldStatus,
            'to_status' => 'ready',
        ]);

        return back()->with('success', 'Zamówienie jest gotowe do odbioru.');
    }
}
