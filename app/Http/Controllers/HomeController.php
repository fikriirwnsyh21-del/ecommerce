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
            ->take(12)
            ->get();

        $newArrivals = Product::with(['shop', 'primaryImage', 'images', 'activeFlashSale'])
            ->where('is_active', true)
            ->latest()
            ->take(12)
            ->get();

        $recommended = Product::with(['shop', 'primaryImage', 'images', 'activeFlashSale'])
            ->where('is_active', true)
            ->orderByDesc('rating_avg')
            ->take(12)
            ->get();

        // Curated Collections for Gen Z Gamers
        $esportsGear = Product::with(['shop', 'primaryImage', 'images', 'activeFlashSale'])
            ->where('is_active', true)
            ->whereHas('category', function ($q) {
                $q->whereIn('slug', ['keyboard-mechanical', 'mouse-gaming', 'monitor-gaming', 'audio-headset']);
            })
            ->take(12)
            ->get();

        $pcBuilders = Product::with(['shop', 'primaryImage', 'images', 'activeFlashSale'])
            ->where('is_active', true)
            ->whereHas('category', function ($q) {
                $q->whereIn('slug', ['pc-rakitan', 'pc-komponen', 'ram-ssd-storage', 'cooling-power-supply']);
            })
            ->take(12)
            ->get();

        $consoleStream = Product::with(['shop', 'primaryImage', 'images', 'activeFlashSale'])
            ->where('is_active', true)
            ->whereHas('category', function ($q) {
                $q->whereIn('slug', ['konsol-handheld', 'streaming-gear', 'racing-sim-vr', 'kursi-setup-meja']);
            })
            ->take(12)
            ->get();

        return view('home', compact(
            'banners',
            'categories',
            'flashSales',
            'flashSaleEndTime',
            'bestSellers',
            'newArrivals',
            'recommended',
            'esportsGear',
            'pcBuilders',
            'consoleStream'
        ));
    }
}
