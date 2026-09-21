<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $shop = $request->user()->shop;

        if (! $shop) {
            abort(403, 'Anda belum memiliki toko terdaftar.');
        }

        // 1. Metrics
        $totalRevenue = OrderItem::where('shop_id', $shop->id)
            ->whereHas('order', function ($q) {
                $q->whereIn('status', ['paid', 'processing', 'shipped', 'delivered', 'completed']);
            })
            ->sum('subtotal');

        $totalOrdersCount = Order::whereHas('items', function ($q) use ($shop) {
            $q->where('shop_id', $shop->id);
        })->count();

        $pendingOrdersCount = Order::whereHas('items', function ($q) use ($shop) {
            $q->where('shop_id', $shop->id);
        })->whereIn('status', ['paid', 'processing'])->count();

        $totalProductsCount = Product::where('shop_id', $shop->id)->count();

        $lowStockCount = Product::where('shop_id', $shop->id)
            ->where('stock', '<=', 5)
            ->count();

        // 2. Recent Orders
        $recentOrders = Order::whereHas('items', function ($q) use ($shop) {
            $q->where('shop_id', $shop->id);
        })
            ->with(['items' => function ($q) use ($shop) {
                $q->where('shop_id', $shop->id)->with('product');
            }, 'user'])
            ->latest()
            ->take(5)
            ->get();

        // 3. Low stock alert products
        $lowStockProducts = Product::where('shop_id', $shop->id)
            ->where('stock', '<=', 5)
            ->take(5)
            ->get();

        return view('seller.dashboard', compact(
            'shop',
            'totalRevenue',
            'totalOrdersCount',
            'pendingOrdersCount',
            'totalProductsCount',
            'lowStockCount',
            'recentOrders',
            'lowStockProducts'
        ));
    }
}
