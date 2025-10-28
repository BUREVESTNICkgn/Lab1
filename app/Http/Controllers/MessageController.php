<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index(Product $product)
    {
        $messages = Message::where('product_id', $product->id)
            ->where(function ($q) {
                $q->where('from_user_id', Auth::id())->orWhere('to_user_id', Auth::id());
            })->orderBy('created_at')->get();
        return view('messages.index', compact('messages', 'product'));
    }

    public function store(Request $request, Product $product)
    {
        $validated = $request->validate(['body' => 'required|string|max:1000']);
        Message::create([
            'from_user_id' => Auth::id(),
            'to_user_id' => $product->user_id,
            'product_id' => $product->id,
            'body' => $validated['body'],
        ]);
        return redirect()->back()->with('success', 'Сообщение отправлено');
    }
}