<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Requests\ProductStoreRequest;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->paginate(9);
        return view('products.index', compact('products'));
    }

    public function search(Request $request)
    {
        $query = Product::query();

        if ($request->filled('keyword')) {
            $keyword = $request->input('keyword');
            $query->where('name', 'like', "%{$keyword}%");
        }

        if ($request->filled('sort')) {
            $sort = $request->input('sort');
            if ($sort === 'price_asc') {
                $query->orderBy('price', 'asc');
            } elseif ($sort === 'price_desc') {
                $query->orderBy('price', 'desc');
            }
        }

        $products = $query->latest()->paginate(9);

        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(ProductStoreRequest $request)
    {
        $product = new Product();
        $product->name = $request->input('name');
        $product->price = $request->input('price');
        $product->description = $request->input('description');

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            // storeAsの第一引数は storage/app からの相対パス
            $image->storeAs('public/images', $filename);
            $product->image = $filename;
        }

        $product->save();

        if ($request->has('seasons')) {
            $product->seasons()->sync($this->getSeasonIds($request->input('seasons')));
        }

        return redirect()->route('products.index')->with('success', '商品を追加しました。');
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(ProductUpdateRequest $request, Product $product)
    {
        $product->name = $request->input('name');
        $product->price = $request->input('price');
        $product->description = $request->input('description');

        if ($request->hasFile('image')) {
            // 古い画像を削除する場合
            if ($product->image) {
                Storage::delete('public/images/' . $product->image);
            }
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/images', $filename);
            $product->image = $filename;
        }

        $product->save();

        $seasons = $request->input('seasons', []);
        $product->seasons()->sync($this->getSeasonIds($seasons));

        return redirect()->route('products.show', $product)->with('success', '商品情報を更新しました。');
    }

    public function destroy(Product $product)
    {
        // 画像を削除
        if ($product->image && Storage::disk('public')->exists('images/' . $product->image)) {
            Storage::disk('public')->delete('images/' . $product->image);
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', '商品を削除しました。');
    }

    private function getSeasonIds(array $seasonNames): array
    {
        // ... (This private method remains unchanged)
        return [];
    }
}
