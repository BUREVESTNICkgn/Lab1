<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::whereNull('expires_at')->orWhere('expires_at', '>', now());
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('title', 'like', "%$search%")->orWhere('description', 'like', "%$search%");
        }
        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }
        if ($request->routeIs('my-products')) {
            $query->where('user_id', Auth::id());
        }
        $products = $query->paginate(10);
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
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'location' => 'required|string|max:255',
            'delivery' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email',
            'category_id' => 'required|exists:categories,id',
            'expires_at' => 'nullable|date|after:now',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $validated['user_id'] = Auth::id();
        $product = Product::create($validated);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('public/images');
                $product->images()->create(['path' => $path]);
            }
        }

        return redirect()->route('products.index')->with('success', 'Товар добавлен');
    }

    public function show(Product $product)
    {
        if ($product->isExpired()) abort(404);
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

        $validated = $request->validate([
            // ... те же правила ...
        ]);

        $product->update($validated);

        if ($request->hasFile('images')) {
            // Удалить старые, если нужно
            foreach ($product->images as $img) {
                Storage::delete($img->path);
                $img->delete();
            }
            foreach ($request->file('images') as $image) {
                $path = $image->store('public/images');
                $product->images()->create(['path' => $path]);
            }
        }

        return redirect()->route('products.index')->with('success', 'Товар обновлён');
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);
        foreach ($product->images as $img) {
            Storage::delete($img->path);
        }
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Товар удалён');
    }
}