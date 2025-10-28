<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Image;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
        $this->middleware('verified');
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $products = Product::with('category', 'images')
            ->when($request->category, fn($query) => $query->where('category_id', $request->category))
            ->when($user, fn($query) => $query->where('user_id', $user->id)->orWhere('expires_at', '>', now()), 'my-products')
            ->where('expires_at', '>', now())
            ->latest()
            ->paginate(10);

        $categories = Category::all();
        return view('products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'images.*' => 'image|max:2048',
            'location' => 'required|string|max:255',
            'delivery' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email',
            'expires_at' => 'required|date|after:today',
        ]);

        $product = Auth::user()->products()->create($request->only([
            'title', 'description', 'price', 'category_id', 'location', 'delivery', 'phone', 'email', 'expires_at'
        ]));

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $product->images()->create(['path' => $path]);
            }
        }

        return redirect()->route('products.show', $product)->with('success', 'Товар добавлен!');
    }

    public function show(Product $product)
    {
        $product->load('user', 'category', 'images', 'messages');
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $this->authorize('update', $product);
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $this->authorize('update', $product);
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'images.*' => 'image|max:2048',
            'location' => 'required|string|max:255',
            'delivery' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email',
            'expires_at' => 'required|date|after:today',
        ]);

        $product->update($request->only([
            'title', 'description', 'price', 'category_id', 'location', 'delivery', 'phone', 'email', 'expires_at'
        ]));

        if ($request->hasFile('images')) {
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->path);
                $image->delete();
            }
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $product->images()->create(['path' => $path]);
            }
        }

        return redirect()->route('products.show', $product)->with('success', 'Товар обновлён!');
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->path);
            $image->delete();
        }
        $product->delete();
        return redirect()->route('my-products')->with('success', 'Товар удалён!');
    }
}