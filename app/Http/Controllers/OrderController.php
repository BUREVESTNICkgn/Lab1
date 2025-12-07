<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
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
        $query = Order::with(['products', 'user'])->latest();

        if (! in_array(Auth::user()->role, ['admin', 'manager'])) {
            $query->where('user_id', Auth::id());
        }

        $orders = $query->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $cart = Session::get('cart', []);
        $products = Product::whereIn('id', array_keys($cart))
            ->where('is_visible', true)
            ->get();

        if ($products->isEmpty()) {
            Session::forget('cart');
            return redirect()->route('products.index')->with('error', 'Товары недоступны или скрыты.');
        }

        $subtotal = $products->sum(function ($product) use ($cart) {
            return $product->price * ($cart[$product->id]['quantity'] ?? 0);
        });

        $shipping = $products->sum(function ($product) use ($cart) {
            return ($product->shipping_cost ?? 0) * ($cart[$product->id]['quantity'] ?? 0);
        });

        $pickupAvailable = $products->every(fn ($product) => $product->pickup_available);
        $total = $subtotal + $shipping;

        return view('orders.create', compact('products', 'cart', 'subtotal', 'shipping', 'total', 'pickupAvailable'));
    }

    public function store(Request $request)
    {
        $cart = Session::get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Корзина пуста');
        }

        $products = Product::whereIn('id', array_keys($cart))
            ->where('is_visible', true)
            ->get();

        if ($products->isEmpty()) {
            Session::forget('cart');
            return redirect()->route('products.index')->with('error', 'Товары недоступны или скрыты.');
        }

        $subtotal = $products->sum(function ($product) use ($cart) {
            return $product->price * ($cart[$product->id]['quantity'] ?? 0);
        });

        $shipping = $products->sum(function ($product) use ($cart) {
            return ($product->shipping_cost ?? 0) * ($cart[$product->id]['quantity'] ?? 0);
        });

        $pickupAvailable = $products->every(fn ($product) => $product->pickup_available);

        $request->validate([
            'shipping_method' => ['required', 'in:delivery,pickup', function ($attribute, $value, $fail) use ($pickupAvailable) {
                if ($value === 'pickup' && ! $pickupAvailable) {
                    $fail('Самовывоз недоступен для выбранных товаров.');
                }
            }],
            'shipping_address' => ['nullable', 'string', 'max:255', 'required_if:shipping_method,delivery'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
        ]);

        $shippingCost = $request->shipping_method === 'pickup' ? 0 : $shipping;

        $order = Order::create([
            'user_id' => Auth::id(),
            'total' => $subtotal + $shippingCost,
            'status' => 'pending',
            'shipping_method' => $request->shipping_method,
            'shipping_cost' => $shippingCost,
            'shipping_address' => $request->shipping_method === 'pickup' ? null : $request->shipping_address,
            'contact_phone' => $request->contact_phone,
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
        if ($order->user_id !== Auth::id() && ! in_array(Auth::user()->role, ['admin', 'manager'])) {
            abort(403);
        }
        $order->load(['products']);
        $order->setRelation('messages', $order->messages()->with('fromUser')->orderBy('created_at')->get());
        return view('orders.show', [
            'order' => $order,
            'canMessage' => in_array(Auth::user()->role, ['admin', 'manager']) || $order->user_id === Auth::id(),
        ]);
    }

    public function edit(Order $order)
    {
        if ($order->user_id !== Auth::id() && ! in_array(Auth::user()->role, ['admin', 'manager'])) {
            abort(403);
        }
        return view('orders.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        if ($order->user_id !== Auth::id() && ! in_array(Auth::user()->role, ['admin', 'manager'])) {
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
        if ($order->user_id !== Auth::id() && ! in_array(Auth::user()->role, ['admin', 'manager'])) {
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

    public function message(Request $request, Order $order)
    {
        if ($order->user_id !== Auth::id() && ! in_array(Auth::user()->role, ['admin', 'manager'])) {
            abort(403);
        }

        $request->validate(['body' => 'required|string|max:2000']);

        $recipientId = $order->user_id;
        if ($order->user_id === Auth::id()) {
            $recipientId = User::whereIn('role', ['manager', 'admin'])->value('id') ?? $order->user_id;
        }

        $order->messages()->create([
            'from_user_id' => Auth::id(),
            'to_user_id' => $recipientId,
            'body' => $request->body,
        ]);

        return redirect()->back()->with('success', 'Сообщение отправлено');
    }
}
