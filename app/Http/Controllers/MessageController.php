<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Product $product)
    {
        $messages = $product->messages()->with(['fromUser', 'toUser'])->get();
        return view('messages.index', compact('product', 'messages'));
    }

    public function store(Request $request, Product $product)
    {
        $request->validate(['body' => 'required|string|max:1000']);
        Message::create([
            'from_user_id' => Auth::id(),
            'to_user_id' => $product->user_id,
            'product_id' => $product->id,
            'body' => $request->body,
        ]);
        return redirect()->back()->with('success', 'Сообщение отправлено!');
    }
}