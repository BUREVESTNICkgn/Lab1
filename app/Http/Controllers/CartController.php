<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        $products = Product::findMany(array_keys($cart))->map(function ($p) use ($cart) {
            $p->quantity = $cart[$p->id];
            return $p;
        });
        $total = $products->sum(fn($p) => $p->price * $p->quantity);
        return view('cart.index', compact('products', 'total'));
    }

    public function add(Request $request, Product $product)
    {
        $cart = session('cart', []);
        $cart[$product->id] = ($cart[$product->id] ?? 0) + ($request->quantity ?? 1);
        session(['cart' => $cart]);
        return redirect()->back()->with('success', 'Добавлено в корзину');
    }

    public function update(Request $request, Product $product)
    {
        $cart = session('cart', []);
        $cart[$product->id] = $request->quantity;
        if ($cart[$product->id] <= 0) unset($cart[$product->id]);
        session(['cart' => $cart]);
        return redirect()->route('cart.index');
    }

    public function remove(Product $product)
    {
        $cart = session('cart', []);
        unset($cart[$product->id]);
        session(['cart' => $cart]);
        return redirect()->route('cart.index')->with('success', 'Удалено из корзины');
    }
}