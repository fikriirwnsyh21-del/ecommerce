<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $query = $request->user()->orders()
            ->with(['items.product.primaryImage', 'items.product.images', 'payment'])
            ->latest();

        if ($request->filled('status') && $request->input('status') !== 'all') {
            $query->where('status', $request->input('status'));
        }

        $orders = $query->paginate(10)->withQueryString();

        return view('customer.orders.index', compact('orders'));
    }

    public function show(Request $request, int $id): View
    {
        $order = Order::with([
            'items.product.shop',
            'items.product.primaryImage',
            'items.product.images',
            'items.review',
            'payment',
            'voucherUsage.voucher',
        ])
            ->where('id', $id)
            ->firstOrFail();

        // Authorize: customer, seller who owns items in this order, or admin
        Gate::authorize('view', $order);

        return view('orders.show', compact('order'));
    }

    /**
     * Simulate instant payment confirmation (works with both form submit and AJAX).
     */
    public function pay(Request $request, int $id): RedirectResponse|JsonResponse
    {
        $order = Order::with('payment')->where('id', $id)->firstOrFail();

        if ($order->user_id !== $request->user()->id && ! $request->user()->isAdmin()) {
            abort(403);
        }

        if ($order->status !== 'pending') {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pesanan ini sudah dibayar atau dibatalkan.',
                ], 400);
            }

            return back()->with('error', 'Pesanan ini sudah dibayar atau dibatalkan.');
        }

        DB::transaction(function () use ($order) {
            $order->update(['status' => 'paid']);

            if ($order->payment) {
                $order->payment->update([
                    'status' => 'paid',
                    'paid_at' => now(),
                ]);
            }
        });

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil diverifikasi secara instan! Pesanan akan segera diproses.',
                'status' => 'paid',
            ]);
        }

        return back()->with('success', 'Pembayaran berhasil dikonfirmasi! Pesanan akan segera diproses oleh penjual.');
    }

    /**
     * Check real-time payment status via AJAX polling.
     */
    public function paymentStatus(Request $request, int $id): JsonResponse
    {
        $order = Order::with('payment')->where('id', $id)->firstOrFail();
        Gate::authorize('view', $order);

        return response()->json([
            'order_id' => $order->id,
            'status' => $order->status,
            'payment_status' => $order->payment?->status ?? 'pending',
            'is_paid' => $order->status === 'paid' || ($order->payment && $order->payment->status === 'paid'),
        ]);
    }

    /**
     * Cancel pending order and restore inventory stock.
     */
    public function cancel(Request $request, int $id): RedirectResponse
    {
        $order = Order::with('items')->where('id', $id)->firstOrFail();

        Gate::authorize('cancel', $order);

        DB::transaction(function () use ($order) {
            $order->update(['status' => 'cancelled']);

            if ($order->payment && $order->payment->status === 'pending') {
                $order->payment->update(['status' => 'failed']);
            }

            // Restore product and variant stock
            foreach ($order->items as $item) {
                Product::where('id', $item->product_id)->increment('stock', $item->quantity);
                Product::where('id', $item->product_id)->decrement('sales_count', $item->quantity);

                if ($item->product_variant_id) {
                    ProductVariant::where('id', $item->product_variant_id)->increment('stock', $item->quantity);
                }
            }
        });

        return back()->with('success', 'Pesanan telah berhasil dibatalkan dan stok telah dikembalikan.');
    }

    /**
     * Confirm delivery by customer to complete the order.
     */
    public function confirmDelivered(Request $request, int $id): RedirectResponse
    {
        $order = Order::where('id', $id)->firstOrFail();

        if ($order->user_id !== $request->user()->id && ! $request->user()->isAdmin()) {
            abort(403);
        }

        if (! in_array($order->status, ['paid', 'processing', 'shipped', 'delivered'], true)) {
            return back()->with('error', 'Hanya pesanan yang sedang berjalan yang dapat diselesaikan.');
        }

        $order->update([
            'status' => 'completed',
            'tracking_number' => $order->tracking_number ?: 'KGZ-EXP-'.strtoupper(bin2hex(random_bytes(4))),
        ]);

        return back()->with('success', 'Terima kasih! Pesanan telah selesai. Silakan berikan ulasan produk.');
    }
}
