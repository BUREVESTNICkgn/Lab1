<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Image;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index(Request $request)
    {
        $query = Product::with(['user', 'category', 'images']);

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $products = $query->latest()->paginate(10);
        $categories = Category::all();

        return view('products.index', compact('products', 'categories'));
    }

    public function myProducts()
    {
        $products = Product::with(['category', 'images'])->where('user_id', Auth::id())->latest()->paginate(10);
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
            'price' => 'required|numeric|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'location' => 'nullable|string|max:255',
            'delivery' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email',
            'expires_at' => 'nullable|date',
            'images.*' => 'image|max:5120',
        ]);

        $product = Product::create([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'location' => $request->location,
            'delivery' => $request->delivery,
            'phone' => $request->phone,
            'email' => $request->email,
            'expires_at' => $request->expires_at,
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,
        ]);

        $this->storeImages($product, $request);

        return redirect()->route('products.index')->with('success', 'Продукт добавлен');
    }

    public function show(Product $product)
    {
        $product->load(['user', 'category', 'images']);
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        if ($product->user_id !== Auth::id()) {
            abort(403);
        }
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        if ($product->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'location' => 'nullable|string|max:255',
            'delivery' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email',
            'expires_at' => 'nullable|date',
            'images.*' => 'image|max:5120',
        ]);

        $product->update($request->only(['title', 'description', 'price', 'location', 'delivery', 'phone', 'email', 'expires_at', 'category_id']));

        $this->storeImages($product, $request);

        return redirect()->route('products.index')->with('success', 'Продукт обновлён');
    }

    public function destroy(Product $product)
    {
        if ($product->user_id !== Auth::id()) {
            abort(403);
        }
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->path);
            $image->delete();
        }
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Продукт удалён');
    }

    protected function storeImages(Product $product, Request $request): void
    {
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('products', 'public');
                Image::create([
                    'product_id' => $product->id,
                    'path' => $path,
                ]);
            }
        }
    }
}
