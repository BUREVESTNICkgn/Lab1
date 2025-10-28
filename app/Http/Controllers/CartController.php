<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $cart = Session::get('cart', []);
        $products = Product::whereIn('id', array_keys($cart))->get();
        $total = collect($cart)->sum(fn($item) => $item['quantity'] * $item['price']);
        return view('cart.index', compact('products', 'cart', 'total'));
    }

    public function add(Product $product)
    {
        $cart = Session::get('cart', []);
        $cart[$product->id] = ['quantity' => ($cart[$product->id]['quantity'] ?? 0) + 1, 'price' => $product->price];
        Session::put('cart', $cart);
        return redirect()->back()->with('success', 'Товар добавлен в корзину!');
    }

    public function update(Request $request, Product $product)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);
        $cart = Session::get('cart', []);
        $cart[$product->id] = ['quantity' => $request->quantity, 'price' => $product->price];
        Session::put('cart', $cart);
        return redirect()->back()->with('success', 'Количество обновлено!');
    }

    public function remove(Product $product)
    {
        $cart = Session::get('cart', []);
        unset($cart[$product->id]);
        Session::put('cart', $cart);
        return redirect()->back()->with('success', 'Товар удалён из корзины!');
    }
}