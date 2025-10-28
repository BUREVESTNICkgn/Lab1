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
        $orders = Order::where('user_id', Auth::id())->paginate(10);
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $cart = session('cart', []);
        if (empty($cart)) return redirect()->route('products.index')->with('error', 'Корзина пуста');
        $products = Product::findMany(array_keys($cart))->map(function ($p) use ($cart) {
            $p->quantity = $cart[$p->id];
            return $p;
        });
        $subtotal = $products->sum(fn($p) => $p->price * $p->quantity);
        $delivery = 500;
        $total = $subtotal + $delivery;
        return view('orders.create', compact('products', 'subtotal', 'delivery', 'total'));
    }

    public function store(Request $request)
    {
        $cart = session('cart', []);
        if (empty($cart)) abort(400);
        $subtotal = array_sum(array_map(fn($id, $qty) => Product::find($id)->price * $qty, array_keys($cart), $cart));
        $total = $subtotal + 500;
        Order::create([
            'user_id' => Auth::id(),
            'items' => $cart,
            'total' => $total,
        ]);
        session()->forget('cart');
        return redirect()->route('my-orders')->with('success', 'Заказ оформлен');
    }
}