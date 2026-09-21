<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $shop = $request->user()->shop;
        abort_if(! $shop, 403);

        $query = Order::whereHas('items', function ($q) use ($shop) {
            $q->where('shop_id', $shop->id);
        })
            ->with(['items' => function ($q) use ($shop) {
                $q->where('shop_id', $shop->id)->with(['product.primaryImage', 'product.images']);
            }, 'user', 'payment'])
            ->latest();

        if ($request->filled('status') && $request->input('status') !== 'all') {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $orders = $query->paginate(10)->withQueryString();

        return view('seller.orders.index', compact('orders'));
    }

    public function show(Request $request, int $id): View
    {
        $shop = $request->user()->shop;
        abort_if(! $shop, 403);

        $order = Order::whereHas('items', function ($q) use ($shop) {
            $q->where('shop_id', $shop->id);
        })
            ->with(['items' => function ($q) use ($shop) {
                $q->where('shop_id', $shop->id)->with(['product.primaryImage', 'product.images']);
            }, 'user', 'payment'])
            ->where('id', $id)
            ->firstOrFail();

        return view('seller.orders.show', compact('order'));
    }

    /**
     * Process order: from 'paid' to 'processing'.
     */
    public function process(Request $request, int $id): RedirectResponse
    {
        $shop = $request->user()->shop;
        abort_if(! $shop, 403);

        $order = Order::whereHas('items', function ($q) use ($shop) {
            $q->where('shop_id', $shop->id);
        })->where('id', $id)->firstOrFail();

        if ($order->status !== 'paid') {
            return back()->with('error', 'Hanya pesanan berstatus Lunas yang dapat diproses.');
        }

        $order->update(['status' => 'processing']);

        return back()->with('success', 'Pesanan telah diterima dan status diubah menjadi Sedang Diproses.');
    }

    /**
     * Ship order: from 'processing' to 'shipped' with tracking number.
     */
    public function ship(Request $request, int $id): RedirectResponse
    {
        $shop = $request->user()->shop;
        abort_if(! $shop, 403);

        $request->validate([
            'tracking_number' => ['required', 'string', 'max:100'],
        ], [
            'tracking_number.required' => 'Nomor resi pengiriman wajib diisi.',
        ]);

        $order = Order::whereHas('items', function ($q) use ($shop) {
            $q->where('shop_id', $shop->id);
        })->where('id', $id)->firstOrFail();

        if ($order->status !== 'processing') {
            return back()->with('error', 'Pesanan harus dalam status Diproses sebelum dapat dikirim.');
        }

        $order->update([
            'status' => 'shipped',
            'tracking_number' => $request->input('tracking_number'),
        ]);

        return back()->with('success', 'Nomor resi berhasil dimasukkan. Pesanan kini Sedang Dikirim ke pembeli!');
    }
}
