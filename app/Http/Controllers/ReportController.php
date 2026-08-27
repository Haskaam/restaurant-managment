<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date', now()->toDateString());

        $orders = Order::where('status', 'closed')
        ->whereDate('closed_at', $date);

        $totalNet = (clone $orders)->sum('total_net');
        $totalVat = (clone $orders)->sum('total_vat');
        $totalGross = (clone $orders)->sum('total_gross');

        $discounts = (clone $orders)->sum('discount_amount');

        $orderCount = (clone $orders)->count();

        $payments = Payment::whereHas('order', function ($query) use ($date) {
            $query->where('status', 'closed')
            ->whereDate('closed_at', $date);
        })
        ->select('method', DB::raw('SUM(amount) as total')
        )->groupBy('method')->get();

        $topDishes = OrderItem::whereHas('order', function ($query) use ($date) {
            $query->where('status', 'closed')
            ->whereDate('closed_at', $date);
        })
        ->select('dish_name', DB::raw('SUM(quantity) as total_quantity')
        )
        ->groupBy('dish_name')
        ->orderByDesc('total_quantity')
        ->limit(5)
        ->get();


        $waiterSales = Order::with('waiter')
        ->where('status', 'closed')
        ->whereDate('closed_at', $date)
        ->select(
            'waiter_id',
            DB::raw('SUM(total_gross) as total_sales'),
            DB::raw('COUNT(*) as order_count')
        )
        ->groupBy('waiter_id')
        ->orderByDesc('total_sales')
        ->get();


        return view('reports.index', compact(
            'date',
            'totalNet',
            'totalVat',
            'totalGross',
            'discounts',
            'orderCount',
            'payments',
            'topDishes',
            'waiterSales'
        ));
    }
}
