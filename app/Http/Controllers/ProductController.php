<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::with(['shop', 'primaryImage', 'images', 'category', 'activeFlashSale'])
            ->where('is_active', true);

        // 1. Search Query
        if ($request->filled('q')) {
            $search = trim($request->string('q'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhereHas('category', function ($catQ) use ($search) {
                        $catQ->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('shop', function ($shopQ) use ($search) {
                        $shopQ->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // 2. Category Filter
        if ($request->filled('category')) {
            $categorySlug = $request->string('category');
            $query->whereHas('category', function ($catQ) use ($categorySlug) {
                $catQ->where('slug', $categorySlug);
            });
        }

        // 3. Price Filter
        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float) $request->input('min_price'));
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float) $request->input('max_price'));
        }

        // 4. Rating Filter
        if ($request->filled('rating')) {
            $query->where('rating_avg', '>=', (float) $request->input('rating'));
        }

        // 5. Location / City Filter
        if ($request->filled('location')) {
            $city = $request->string('location');
            $query->whereHas('shop', function ($shopQ) use ($city) {
                $shopQ->where('city', 'like', "%{$city}%");
            });
        }

        // 6. Stock Availability Filter
        if ($request->filled('stock') && $request->input('stock') === 'ready') {
            $query->where('stock', '>', 0);
        }

        // 7. Flash Sale Filter
        if ($request->input('filter') === 'flash_sale') {
            $query->whereHas('activeFlashSale');
        }

        // 8. Sorting
        match ($request->input('sort')) {
            'cheapest' => $query->orderBy('price', 'asc'),
            'expensive' => $query->orderBy('price', 'desc'),
            'popular' => $query->orderByDesc('sales_count'),
            'rating' => $query->orderByDesc('rating_avg'),
            'newest' => $query->latest(),
            default => $query->latest(),
        };

        $products = $query->paginate(20)->withQueryString();

        // Data for sidebar filters
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $cities = Shop::select('city')->distinct()->pluck('city')->filter()->values();

        return view('products.index', compact('products', 'categories', 'cities'));
    }

    public function show(string $slug): View
    {
        $product = Product::with([
            'shop',
            'category',
            'images',
            'primaryImage',
            'variants',
            'activeFlashSale',
            'reviews.user',
        ])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Increment view count
        $product->increment('views_count');

        // Related products in the same category
        $relatedProducts = Product::with(['shop', 'primaryImage', 'images', 'activeFlashSale'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }
}
