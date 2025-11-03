<?php

namespace App\Http\Controllers;

use App\Models\Category;  // ← добавлено
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['user', 'category']);

        // Фильтр по категории, если передана
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->paginate(10);
        $categories = Category::all();  // ← добавлено: все категории

        return view('products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();  // ← для формы создания
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'nullable|exists:categories,id',  // ← добавлено
        ]);

        Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,  // ← добавлено
        ]);

        return redirect()->route('products.index')
            ->with('success', 'Продукт добавлен');
    }

    public function show(Product $product)
    {
        $product->load(['user', 'category']);
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $this->authorize('update', $product);
        $categories = Category::all();  // ← для формы редактирования
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'nullable|exists:categories,id',  // ← добавлено
        ]);

        $product->update($request->only(['name', 'description', 'price', 'category_id']));

        return redirect()->route('products.index')
            ->with('success', 'Продукт обновлён');
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);
        $product->delete();
        return redirect()->route('products.index')
            ->with('success', 'Продукт удалён');
    }
}