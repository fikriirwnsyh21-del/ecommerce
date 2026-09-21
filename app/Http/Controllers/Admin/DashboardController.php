<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        // 1. Platform Metrics
        $totalGmv = Order::whereIn('status', ['paid', 'processing', 'shipped', 'delivered', 'completed'])->sum('grand_total');
        $totalOrdersCount = Order::count();
        $totalUsersCount = User::count();
        $totalShopsCount = Shop::where('is_active', true)->count();
        $totalProductsCount = Product::count();

        // 2. Order distribution by status
        $pendingOrders = Order::where('status', 'pending')->count();
        $paidOrders = Order::where('status', 'paid')->count();
        $completedOrders = Order::where('status', 'completed')->count();

        // 3. Recent Transactions
        $recentOrders = Order::with(['user', 'payment', 'items.shop'])
            ->latest()
            ->take(6)
            ->get();

        // 4. Categories Overview
        $topCategories = Category::withCount('products')
            ->orderByDesc('products_count')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalGmv',
            'totalOrdersCount',
            'totalUsersCount',
            'totalShopsCount',
            'totalProductsCount',
            'pendingOrders',
            'paidOrders',
            'completedOrders',
            'recentOrders',
            'topCategories'
        ));
    }
}
