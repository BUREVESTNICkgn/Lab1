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
        $orders = Auth::user()->orders()->latest()->get();
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $cart = Session::get('cart', []);
        if (empty($cart)) return redirect()->route('cart.index')->with('error', 'Корзина пуста!');
        $products = Product::whereIn('id', array_keys($cart))->get();
        $total = collect($cart)->sum(fn($item) => $item['quantity'] * $item['price']);
        return view('orders.create', compact('products', 'cart', 'total'));
    }

    public function store(Request $request)
    {
        $cart = Session::get('cart', []);
        if (empty($cart)) return redirect()->route('cart.index')->with('error', 'Корзина пуста!');
        $total = collect($cart)->sum(fn($item) => $item['quantity'] * $item['price']);
        $order = Auth::user()->orders()->create([
            'items' => $cart,
            'total' => $total,
        ]);
        Session::forget('cart');
        return redirect()->route('my-orders')->with('success', 'Заказ оформлен!');
    }
}