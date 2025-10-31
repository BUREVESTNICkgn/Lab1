<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user', 'products')->paginate(10);
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $products = Product::all();
        return view('orders.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'products' => 'required|array',
            'products.*.id' => 'exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $total = 0;
        $items = [];

        foreach ($request->products as $item) {
            $product = Product::find($item['id']);
            $price = $product->price * $item['quantity'];
            $total += $price;
            $items[$product->id] = ['quantity' => $item['quantity'], 'price' => $product->price];
        }

        $order = Order::create([
            'user_id' => Auth::id(),
            'total' => $total,
            'status' => 'pending',
        ]);

        $order->products()->attach($items);

        return redirect()->route('orders.index')->with('success', 'Заказ создан');
    }

    public function show(Order $order)
    {
        $order->load('products');
        return view('orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $this->authorize('update', $order);
        $products = Product::all();
        return view('orders.edit', compact('order', 'products'));
    }

    public function update(Request $request, Order $order)
    {
        $this->authorize('update', $order);

        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        $order->update($request->only('status'));

        return redirect()->route('orders.index')->with('success', 'Статус обновлён');
    }

    public function destroy(Order $order)
    {
        $this->authorize('delete', $order);
        $order->products()->detach();
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Заказ удалён');
    }
}