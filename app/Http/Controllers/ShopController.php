<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function show(string $slug, Request $request): View
    {
        $shop = Shop::withCount(['products' => function ($q) {
            $q->where('is_active', true);
        }])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $query = $shop->products()
            ->with(['primaryImage', 'images', 'category', 'activeFlashSale'])
            ->where('is_active', true);

        // Search within shop
        if ($request->filled('q')) {
            $search = trim($request->string('q'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Category filter within shop
        if ($request->filled('category')) {
            $categorySlug = $request->string('category');
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        // Sorting
        match ($request->input('sort')) {
            'cheapest' => $query->orderBy('price', 'asc'),
            'expensive' => $query->orderBy('price', 'desc'),
            'popular' => $query->orderByDesc('sales_count'),
            default => $query->latest(),
        };

        $products = $query->paginate(12)->withQueryString();

        // Shop categories
        $categories = Category::whereHas('products', function ($q) use ($shop) {
            $q->where('shop_id', $shop->id)->where('is_active', true);
        })->get();

        return view('shops.show', compact('shop', 'products', 'categories'));
    }
}
