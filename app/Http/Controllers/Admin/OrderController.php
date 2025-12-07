<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('staff');
    }

    public function index()
    {
        $orders = Order::with(['products', 'user'])->latest()->paginate(15);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['products', 'user']);
        $order->setRelation('messages', $order->messages()->with('fromUser')->orderBy('created_at')->get());
        return view('orders.show', [
            'order' => $order,
            'canMessage' => true,
        ]);
    }

    public function update(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        $order->update($data);

        return redirect()->route('staff.orders.index')->with('success', 'Статус заказа обновлён');
    }
}
