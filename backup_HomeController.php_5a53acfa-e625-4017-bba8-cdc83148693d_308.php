<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\FlashSale;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $banners = Banner::where('is_active', true)
            ->where('position', 'hero')
            ->orderBy('sort_order')
            ->get();

        $categories = Category::where('is_active', true)
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->take(10)
            ->get();

        $flashSales = FlashSale::with(['product' => function ($query) {
            $query->with(['shop', 'primaryImage', 'images']);
        }])
            ->where('is_active', true)
            ->where('start_time', '<=', now())
            ->where('end_time', '>=', now())
            ->take(6)
            ->get();

        $flashSaleEndTime = $flashSales->isNotEmpty()
            ? $flashSales->min('end_time')->toIso8601String()
            : now()->addHours(6)->toIso8601String();

        $bestSellers = Product::with(['shop', 'primaryImage', 'images', 'activeFlashSale'])
            ->where('is_active', true)
            ->orderByDesc('sales_count')
            ->take(8)
            ->get();

        $newArrivals = Product::with(['shop', 'primaryImage', 'images', 'activeFlashSale'])
            ->where('is_active', true)
            ->latest()
            ->take(8)
            ->get();

        $recommended = Product::with(['shop', 'primaryImage', 'images', 'activeFlashSale'])
            ->where('is_active', true)
            ->orderByDesc('rating_avg')
            ->take(8)
            ->get();

        return view('home', compact(
            'banners',
            'categories',
            'flashSales',
            'flashSaleEndTime',
            'bestSellers',
            'newArrivals',
            'recommended'
        ));
    }
}
