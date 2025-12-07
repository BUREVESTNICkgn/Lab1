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
        $products = Product::whereIn('id', array_keys($cart))
            ->where('is_visible', true)
            ->get();

        $products->each(function ($product) use ($cart) {
            $product->quantity = $cart[$product->id]['quantity'] ?? 0;
        });

        $total = $products->sum(fn($product) => $product->price * $product->quantity);
        return view('cart.index', compact('products', 'cart', 'total'));
    }

    public function add(Request $request, Product $product)
    {
        if (! $product->is_visible) {
            abort(404);
        }

        $quantity = max((int) $request->input('quantity', 1), 1);
        $cart = Session::get('cart', []);
        $cart[$product->id] = [
            'quantity' => ($cart[$product->id]['quantity'] ?? 0) + $quantity,
            'price' => $product->price,
        ];
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
