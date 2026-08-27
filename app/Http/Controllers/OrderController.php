<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Dish;
use App\Models\Order;
use App\Models\OrderItem;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['waiter', 'items'])->get();

        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $dishes = Dish::with('category')
        ->where('is_available', true)
        ->get();

        return view('orders.create', compact('dishes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.dish_id' => ['required', 'exists:dishes,id'],
            'items.*.quantity' => ['required', 'integer', 'min:0'],
            'items.*.notes' => ['nullable', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($data, $request) {
            $order = Order::create([
                'waiter_id' => $request->user()->id,
                'status' => 'accepted',
                'accepted_at' => now(),
            ]);

            $order->statusHistory()->create([
                'changed_by' => $request->user()->id,
                'from_status' => null,
                'to_status' => 'accepted',
            ]);

            $subtotalNet = 0;
            $subtotalVat = 0;
            $subtotalGross = 0;

            foreach ($data['items'] as $item) {
                if ($item['quantity'] < 1) {
                    continue;
                }


                $dish = Dish::where('id', $item['dish_id'])
                ->where('is_available', true)
                ->firstOrFail();

                $quantity = $item['quantity'];

                $unitNet = $dish->net_price;

                $unitVat = $unitNet * ($dish->vat_rate / 100);

                $unitGross = $unitNet + $unitVat;

                $totalNet = $unitNet * $quantity;
                $totalVat = $unitVat * $quantity;
                $totalGross = $unitGross * $quantity;

                OrderItem::create([
                    'order_id' => $order->id,
                    'dish_id' => $dish->id,

                    'dish_name' => $dish->name,
                    'quantity' => $quantity,

                    'unit_net_price' => $unitNet,
                    'vat_rate' => $dish->vat_rate,
                    'unit_gross_price' => $unitGross,

                    'total_net' => $totalNet,
                    'total_vat' => $totalVat,
                    'total_gross' => $totalGross,

                    'notes' => $item['notes'] ?? null,
                ]);

                $subtotalNet += $totalNet;
                $subtotalVat += $totalVat;
                $subtotalGross += $totalGross;
            }

            $order->update([
                'subtotal_net' => $subtotalNet,
                'subtotal_vat' => $subtotalVat,
                'subtotal_gross' => $subtotalGross,

                'total_net' => $subtotalNet,
                'total_vat' => $subtotalVat,
                'total_gross' => $subtotalGross,
            ]);
        });

        return redirect()
        ->route('orders.index')
        ->with('success', 'Zamówienie zostało utworzone.');
    }

    public function collect(Request $request, Order $order)
    {
        if ($order->waiter_id !== $request->user()->id) {
            abort(403);
        }

        if ($order->status !== 'ready') {
            return back()->with('error', 'Zamówienie nie jest jeszcze gotowe.');
        }

        $oldStatus = $order->status;

        $order->update([
            'status' => 'collected',
            'collected_at' => now(),
        ]);

        $order->statusHistory()->create([
            'changed_by' => request()->user()->id,
            'from_status' => $oldStatus,
            'to_status' => 'collected',
        ]);

        return back()->with('success', 'Zamówienie zostało odebrane.');
    }



    public function addPayment(Request $request, Order $order)
    {
        $data = $request->validate([
            'method' => ['required', 'in:cash,card,voucher'],
            'amount' => ['required', 'numeric', 'min:0.01'],
        ]);

        $order->payments()->create([
            'created_by' => $request->user()->id,
            'method' => $data['method'],
            'amount' => $data['amount'],
        ]);

        return back()->with('success', 'Płatność została dodana.');
    }



    public function close(Order $order)
    {
        if ($order->status !== 'collected') {
            return back()->with('error', 'Zamówienie nie może zostać jeszcze zamknięte.');
        }

        $paidAmount = $order->payments()->sum('amount');

        if ($paidAmount < $order->total_gross) {
            return back()->with('error', 'Płatność nie pokrywa wartości zamówienia.');
        }

        $oldStatus = $order->status;

        $order->update([
            'status' => 'closed',
            'closed_at' => now(),
        ]);

        $order->statusHistory()->create([
            'changed_by' => request()->user()->id,
            'from_status' => $oldStatus,
            'to_status' => 'closed',
        ]);

        return back()->with('success', 'Zamówienie zostało zamknięte.');
    }



    public function payment(Request $request, Order $order)
    {
        if($order->waiter_id !== $request->user()->id) {
            abort(403);
        }

        if ($order->status !== 'collected') {
            return redirect()->route('orders.index')->with('error', 'Tego zamówienia nie można teraz rozliczyć');
        }

        $order->load('payments');

        return view('orders.payment', compact('order'));
    }

    public function processPayment(Request $request, Order $order)
{
    if ($order->waiter_id !== $request->user()->id) {
        abort(403);
    }

    if ($order->status !== 'collected') {
        return redirect()
            ->route('orders.index')
            ->with('error', 'Tego zamówienia nie można teraz rozliczyć.');
    }

    $data = $request->validate([
        'method' => ['required', 'in:cash,card,voucher'],
        'amount' => ['required', 'numeric', 'min:0.01'],
    ]);

    $alreadyPaid = $order->payments()->sum('amount');

    $remaining = round(
        $order->total_gross - $alreadyPaid,
        2
    );

    if ($data['amount'] > $remaining) {
        return back()
            ->withErrors([
                'amount' => 'Kwota płatności jest większa niż pozostała kwota zamówienia.',
            ])
            ->withInput();
    }

    $order->payments()->create([
        'created_by' => $request->user()->id,
        'method' => $data['method'],
        'amount' => $data['amount'],
    ]);

    $paidAfterPayment = round(
        $alreadyPaid + $data['amount'],
        2
    );

    if ($paidAfterPayment >= $order->total_gross) {

        $order->update([
            'status' => 'closed',
            'closed_at' => now(),
        ]);

        return redirect()
            ->route('orders.index')
            ->with('success', 'Zamówienie zostało rozliczone i zamknięte.');
    }

    return redirect()
        ->route('orders.payment', $order)
        ->with('success', 'Płatność została dodana.');
}


public function applyDiscount(Request $request, Order $order)
{
    if ($order->waiter_id !== $request->user()->id) {
        abort(403);
    }

    if ($order->status !== 'collected') {
        return back()->with(
            'error',
            'Rabat można zastosować tylko podczas rozliczania zamówienia.'
        );
    }

    $data = $request->validate([
        'discount_percent' => [
            'required',
            'numeric',
            'min:0',
            'max:100',
        ],

        'discount_reason' => [
            'required',
            'string',
            'max:255',
        ],
    ]);

    if ($order->payments()->exists()) {
    return back()->with(
        'error',
        'Nie można zmienić rabatu po rozpoczęciu płatności.'
    );
}

    $percent = $data['discount_percent'] / 100;

    $discountAmount =
        $order->subtotal_gross * $percent;

    $totalNet =
        $order->subtotal_net * (1 - $percent);

    $totalVat =
        $order->subtotal_vat * (1 - $percent);

    $totalGross =
        $order->subtotal_gross * (1 - $percent);

    $order->update([
        'discount_percent' => $data['discount_percent'],
        'discount_reason' => $data['discount_reason'],
        'discount_amount' => round($discountAmount, 2),

        'total_net' => round($totalNet, 2),
        'total_vat' => round($totalVat, 2),
        'total_gross' => round($totalGross, 2),
    ]);

    return back()->with(
        'success',
        'Rabat został zastosowany.'
    );
}

}
