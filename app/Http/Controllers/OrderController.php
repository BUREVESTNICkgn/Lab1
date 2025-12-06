<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $orders = Order::with(['products', 'user'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $cart = Session::get('cart', []);
        $products = Product::whereIn('id', array_keys($cart))->get();

        $subtotal = $products->sum(function ($product) use ($cart) {
            return $product->price * ($cart[$product->id]['quantity'] ?? 0);
        });

        $shipping = $products->isEmpty() ? 0 : 500;
        $total = $subtotal + $shipping;

        return view('orders.create', compact('products', 'cart', 'subtotal', 'shipping', 'total'));
    }

    public function store(Request $request)
    {
        $cart = Session::get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Корзина пуста');
        }

        $products = Product::whereIn('id', array_keys($cart))->get();

        $total = $products->sum(function ($product) use ($cart) {
            return $product->price * ($cart[$product->id]['quantity'] ?? 0);
        });

        $shipping = 500;
        $order = Order::create([
            'user_id' => Auth::id(),
            'total' => $total + $shipping,
            'status' => 'pending',
        ]);

        $pivotData = [];
        foreach ($products as $product) {
            $pivotData[$product->id] = [
                'quantity' => $cart[$product->id]['quantity'],
                'price' => $product->price,
            ];
        }

        $order->products()->attach($pivotData);

        Session::forget('cart');

        return redirect()->route('orders.index')->with('success', 'Заказ создан');
    }

    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        $order->load('products');
        return view('orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        return view('orders.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        $order->update($request->only('status'));

        return redirect()->route('orders.index')->with('success', 'Статус обновлён');
    }

    public function destroy(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        $order->products()->detach();
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Заказ удалён');
    }

    public function myOrders()
    {
        return $this->index();
    }
}
